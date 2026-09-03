# Deployment (staging server)

The server pulls from GitHub. You never edit code on the server directly.

## One-time setup

Connect the existing server directory to Git. `.env`, `vendor/`, uploads and
`storage/` are all git-ignored, so this does not touch them.

```bash
cd /home/lookco02/4bstudio.com.ua/first

# Safety copy first
cp .env ~/env-backup-$(date +%F)
tar czf ~/first-files-backup-$(date +%F).tgz --exclude=vendor --exclude=node_modules .

git init
git remote add origin https://github.com/proextreme/1723.git
git fetch origin
git checkout -b main origin/main
# If Git refuses because skeleton files differ from the repo:
#   git reset --hard origin/main      # safe: .env / uploads are git-ignored

php artisan storage:link
```

Confirm the web server's document root is:
`/home/lookco02/4bstudio.com.ua/first/public`

## Every deploy after that

After Claude pushes to `main`:

```bash
cd /home/lookco02/4bstudio.com.ua/first
bash scripts/deploy.sh
```

The script:

1. backs up `.env`
2. `git pull` the pushed commit
3. `composer install --no-dev --optimize-autoloader`
4. shows pending migrations + an SQL dry run, then **asks you to type `yes`**
   before applying them (type anything else to skip)
5. `storage:link` if missing
6. rebuilds config/route/view caches
7. prints `php artisan about` and the site URL

If `php`/`composer` are not on PATH, run with explicit binaries:

```bash
PHP_BIN=/path/to/php COMPOSER_BIN=/path/to/composer bash scripts/deploy.sh
```

## Notes

- The server has no Node/npm. Once the frontend build starts, compiled assets
  (`public/build`) will be committed to the repo so `git pull` delivers them.
- Never run `migrate:fresh`, `db:wipe`, or `migrate:rollback` on this server.
- If a deploy goes wrong: `git reset --hard <previous-short-sha>` restores the
  code; restore `.env` from the newest `.env.backup.*` if needed.
