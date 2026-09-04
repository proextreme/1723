# Deployment (staging server)

The server pulls from GitHub. You never edit code on the server directly.

Site: `https://fond.4bstudio.com.ua/`  (live since 2026-09-04)
Path: `/home/lookco02/4bstudio.com.ua/fond`
Database: MySQL `lookco02_firstmain` (schema already migrated).

The earlier `first.4bstudio.com.ua` target was abandoned: it was on Apache with
web PHP stuck at 8.3 (Symfony 8 needs >= 8.4.1) and its move to a dedicated IP
broke the wildcard TLS. `fond` runs nginx + PHP 8.4 and keeps the shared IP.

## Why the root `.htaccess`

This host serves each site from the site folder itself (no adjustable document
root, no `public_html`). The committed root `.htaccess` rewrites every request
into `public/`, so Laravel's front controller runs and dotfiles above `public/`
stay unreachable. Same trick the working `neon.4bstudio.com.ua` site uses.

Keep `fond` on the shared IP: the wildcard cert `*.4bstudio.com.ua` only covers
sites on the shared IP. (The `first` attempt broke its own TLS by moving to a
dedicated IP.) The site's PHP version must be 8.4+ (Symfony 8 needs `>=8.4.1`);
`fond` already runs 8.4 on the web.

## One-time setup

```bash
cd /home/lookco02/4bstudio.com.ua/fond
rm -f _v.php                       # remove any leftover probe
git clone https://github.com/proextreme/1723.git .

cp .env.example .env
php artisan key:generate           # REQUIRED - a missing APP_KEY is a 500 on every page
sed -i 's#^APP_ENV=.*#APP_ENV=staging#'                       .env
sed -i 's#^APP_DEBUG=.*#APP_DEBUG=false#'                     .env
sed -i 's#^APP_URL=.*#APP_URL=https://fond.4bstudio.com.ua#'  .env
# then edit .env by hand: DB_CONNECTION=mysql, DB_DATABASE=lookco02_firstmain,
# DB_USERNAME / DB_PASSWORD / DB_HOST from the hosting panel (MySQL section).

bash scripts/deploy.sh
```

Finally, in the panel for this site: enable **HTTPS redirect (http -> https)**.
Do NOT switch the site to a dedicated IP - the wildcard cert only covers the
shared IP.

## Every deploy after that

After Claude pushes to `main`:

```bash
cd /home/lookco02/4bstudio.com.ua/fond
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
