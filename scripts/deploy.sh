#!/bin/bash
#
# Deploy the currently pushed "main" to this server.
#
# Usage (on the server):
#   cd /home/lookco02/4bstudio.com.ua/first
#   bash scripts/deploy.sh
#
# Safe to re-run. It never touches .env, uploaded files, or the database
# schema without an explicit confirmation.

set -euo pipefail

# Always run from the project root (the directory that contains this script's parent).
cd "$(dirname "$0")/.."
PROJECT_ROOT="$(pwd)"
echo ">> Project: $PROJECT_ROOT"

PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

# ---------------------------------------------------------------------------
# 1. Backup .env
# ---------------------------------------------------------------------------
STAMP="$(date +%Y-%m-%d-%H%M%S)"
if [ -f .env ]; then
    cp .env ".env.backup.$STAMP"
    echo ">> .env backed up to .env.backup.$STAMP"
else
    echo "!! .env is missing - aborting" >&2
    exit 1
fi

# ---------------------------------------------------------------------------
# 2. Pull the approved commit
# ---------------------------------------------------------------------------
echo ">> Fetching origin/main"
git fetch origin
echo ">> Currently on commit: $(git rev-parse --short HEAD)"
echo ">> origin/main is at:  $(git rev-parse --short origin/main)"
git checkout main
git pull --ff-only origin main
echo ">> Now on commit: $(git rev-parse --short HEAD)"

# ---------------------------------------------------------------------------
# 3. PHP dependencies
# ---------------------------------------------------------------------------
echo ">> composer install (no-dev)"
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction

# ---------------------------------------------------------------------------
# 4. Migrations - review, then confirm
# ---------------------------------------------------------------------------
echo ">> Pending migrations:"
$PHP_BIN artisan migrate:status || true
echo
echo ">> Dry run of pending migrations (SQL preview):"
$PHP_BIN artisan migrate --pretend --force || true
echo
read -r -p ">> Run these migrations against the production database? (type: yes) " ANSWER
if [ "$ANSWER" = "yes" ]; then
    $PHP_BIN artisan migrate --force
    echo ">> Migrations applied"
else
    echo ">> Skipped migrations"
fi

# ---------------------------------------------------------------------------
# 5. Storage symlink (idempotent)
# ---------------------------------------------------------------------------
if [ ! -L public/storage ]; then
    $PHP_BIN artisan storage:link
    echo ">> storage:link created"
fi

# ---------------------------------------------------------------------------
# 6. Rebuild caches
# ---------------------------------------------------------------------------
echo ">> Rebuilding caches"
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache || true

# ---------------------------------------------------------------------------
# 7. Report
# ---------------------------------------------------------------------------
echo
echo ">> Deploy finished. Application summary:"
$PHP_BIN artisan about
echo
echo ">> Verify: https://fond.4bstudio.com.ua/"
