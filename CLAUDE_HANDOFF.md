# Project 1723 - Claude Code Handoff

Read this document before changing any files or running commands.

## Role

Act as a Senior Laravel Architect and Senior Backend Developer. Follow official Laravel documentation for the installed version and the official Laravel AI-agent guidance at:

- https://laravel.com/for/agents
- https://laravel.com/docs/13.x

Use standard Laravel structure. Do not create a custom framework inside Laravel. Avoid unnecessary packages, global refactors, and speculative abstractions.

## Product

Project name: 1723.

This is an English-first presentation and editorial website for a magazine. It includes editorial content, print edition showcases, brand partnerships, external calls to action, and a small admin panel for managing content.

The first stage must not include:

- custom submission forms;
- custom payments;
- user accounts for visitors;
- an online store;
- a custom checkout;
- a custom submission system.

Main submission and purchase actions lead to external services such as Kavyar and MagCloud.

The architecture must allow future additions without rewriting the application from zero: owned submissions, online payments, visitor accounts, and a full e-commerce system.

## Public site structure

### Home

- Hero;
- magazine statement, using a meaningful `h1`;
- editorial preview;
- covers preview;
- print editions preview;
- final CTA;
- email subscription block.

### Submit

Two external directions:

- Editorial Submission -> Kavyar;
- Advertorial Placement -> Kavyar.

The page includes a short description, several images, and an editorial note.

### Partnerships

For brands, agencies, and commercial clients:

- collaboration description;
- Advertorial;
- Cover Features;
- Instagram Promotion;
- Interviews & Special Projects;
- Media Kit PDF download;
- final CTA.

### Print

- current issue;
- previous issues;
- cover images;
- links to MagCloud;
- MagCloud links open in a new tab.

### Editorial

A periodically changing feed of:

- articles;
- interviews;
- editorials.

The article detail page includes:

- text;
- photo gallery;
- Credits block with links;
- Submit Your Work CTA.

### Footer on every page

- Instagram;
- Email;
- Print Editions / MagCloud;
- Media Kit.

## Design

The design is being created in Figma and must be reproduced accurately on desktop and mobile. Do not invent a replacement visual system when the Figma design is available.

Figma file:

- https://www.figma.com/design/DukVVUXeMxpWcH32jDvVpj/colins-2024?node-id=0-1&t=6JGIbH0txNtfMl65-1

Relevant frames:

- Desktop Home: https://www.figma.com/design/DukVVUXeMxpWcH32jDvVpj/colins-2024?node-id=1529-210&t=6JGIbH0txNtfMl65-1
- Mobile Home: https://www.figma.com/design/DukVVUXeMxpWcH32jDvVpj/colins-2024?node-id=1529-319&t=6JGIbH0txNtfMl65-1
- Expanded menu: https://www.figma.com/design/DukVVUXeMxpWcH32jDvVpj/colins-2024?node-id=1529-454&t=6JGIbH0txNtfMl65-1

Use real exported assets, supplied fonts, and Figma measurements where available. Ask only for assets or design decisions that cannot be inferred safely. The first Home design visibly includes magazine branding, a statement section, front covers, print editions, and a final CTA.

Frontend requirements:

- responsive desktop and mobile layouts;
- keyboard accessible navigation and controls;
- loading, empty, error, and success states where applicable;
- no business rules duplicated only in JavaScript;
- all user input validated on the backend;
- no overlapping or clipped text;
- stable dimensions for media and layout-critical elements;
- semantic HTML and correct heading hierarchy.

Preferred first-stage frontend approach: Laravel Blade + Vite with server-rendered pages. Use a frontend SPA only if the Figma interactions require it.

## Language strategy

The first public language is English (`en`). Prepare for future languages without creating `title_en`, `body_en`, etc. columns.

Use Laravel language files for interface strings and translation tables for database content. Do not add language switching UI until a second language is actually required.

## Admin panel

Use the name "Admin Panel" in product language.

There are two roles:

### Content Administrator

- create and edit articles;
- upload and manage article media;
- edit credits;
- submit articles for review;
- publish articles.

### Administrator

- all Content Administrator capabilities;
- manage Print Editions;
- manage external links;
- manage Media Kit;
- manage users and roles;
- manage site settings.

Article workflow:

`draft -> review -> published`

Both roles may publish content. Implement authorization through Laravel Policies and explicit server-side validation, not scattered role checks in controllers.

The content editor should be visual and comfortable for a content manager, similar in convenience to WordPress. Store sanitized content. Never render untrusted raw HTML without sanitization.

Email provider is not selected yet. Keep newsletter integration behind an application boundary and do not hard-code a provider until it is chosen.

Cookie consent is required on the first stage. Analytics and tracking are not required yet.

Media Kit is assumed to be one current PDF file. Version history is not required unless later requested.

## Database decisions

Production database: MySQL/MariaDB on the hosting provider.

Current schema direction:

- `users`: standard Laravel users plus `role` string (`administrator` or `content_administrator`);
- `articles`: status, published_at, created_by, updated_by, timestamps, soft deletes;
- `article_translations`: article_id, locale, title, slug, excerpt, body_html, SEO fields, unique article/locale and locale/slug;
- `media`: disk, path, original filename, MIME type, size, dimensions, alt text, created_by, timestamps, soft deletes;
- `article_media`: article/media pivot, sort order, featured flag, caption;
- `article_credits`: article_id, label, name, optional URL, sort order;
- `print_editions`: issue number, release date, MagCloud URL, cover media, current flag, sort order, timestamps, soft deletes;
- `print_edition_translations`: print edition, locale, title, slug, description;
- `site_links`: unique key, label, URL, target, optional media attachment, active flag.

Use strings with validation instead of database-specific MySQL ENUMs for roles and statuses. Keep foreign keys, indexes, unique constraints, nullability, delete behavior, and transaction boundaries explicit.

Only one Print Edition may be current. Changing it must be atomic and protected against race conditions.

Do not create newsletter subscription, payment, order, visitor account, or custom submission tables until the corresponding feature is approved.

Do not use soft deletes indiscriminately. They are intended for articles, print editions, and media where recovery/audit value exists.

## Security and quality requirements

- Laravel authentication for the Admin Panel;
- policies and ownership checks;
- CSRF protection for web forms;
- Form Requests and backend validation;
- escaped Blade output and sanitized rich content;
- rate limiting for newsletter actions and other public forms;
- strict upload MIME/type/size/dimension validation;
- secure file names and Laravel Filesystem;
- user files outside the public project root where appropriate;
- no secrets in Git;
- `.env` is never committed;
- log critical administrative actions;
- protect webhook endpoints if introduced later;
- use idempotency for future payments/webhooks;
- avoid mass assignment vulnerabilities;
- avoid SQL injection by using Eloquent/query builder bindings;
- prevent N+1 queries with focused eager loading and selected columns;
- use transactions for atomic multi-write operations;
- write automated tests for important scenarios.

Do not run destructive database commands on the remote server without an explicit warning and confirmation. In particular, never run `migrate:fresh`, `db:wipe`, or similar commands on the remote database.

## Current environment and repository

Local development directory:

`C:\Work\1723` (actual Windows path: `C:\Работа\1723`)

GitHub repository:

`https://github.com/proextreme/1723`

Branch: `main`.

Latest pushed commit:

`4c57751 Add CMS content data model`

The project is Laravel `13.30.1` with PHP `8.5+`, Composer, Laravel Boost `2.7.0`, PHPUnit, and npm available locally. The local project uses SQLite for development/tests. The production/staging server uses MySQL.

The local `.env` is ignored by Git and must remain local. Never replace it with production credentials.

Important current state:

- Laravel skeleton was created and pushed;
- Boost was installed and Boost commands are available locally;
- CMS migrations and Eloquent models were created and pushed;
- local SQLite migrations passed;
- PHPUnit previously passed with `2 tests, 2 assertions`;
- factories for CMS entities were generated and edited after the latest commit, so check `git status` before changing or committing anything;
- the local Composer post-install scripts have a Windows PATH issue with `@php`; use the absolute PHP binary when needed:
  `C:\Users\Арсен\.config\herd-lite\bin\php.exe`;
- the server project exists at `/home/lookco02/4bstudio.com.ua/first`;
- the server runs PHP `8.5.9`, Composer `2.10.2`, Laravel `13.30.1`;
- the server is connected to MySQL database `lookco02_firstmain` at host `lookco02.mysql.tools`;
- the server has no Node.js/npm;
- the server Document Root must be `/home/lookco02/4bstudio.com.ua/first/public`;
- the server currently has `APP_ENV=staging` and `APP_DEBUG=false`;
- the server `.env` and database password must never be copied to GitHub or this document.

The server directory was not yet safely connected to Git and must not be overwritten blindly. Before the first deployment, inspect the server state and make a dated backup of project files if needed. Preserve `.env`, uploaded files, and database data.

## Recommended development order

1. Run `git status` and inspect the current tree.
2. Read local `CLAUDE.md`, `AGENTS.md`, `.ai/rules/index.md`, and relevant rules if present.
3. Run `git log --oneline -5`.
4. Finish and validate factories and seeders.
5. Add focused feature tests for models, authorization, publishing workflow, and public visibility of published content.
6. Decide whether a third-party Admin Panel package is needed. Do not add Filament or another package without checking Laravel 13 compatibility and explaining the reason.
7. Implement the Admin Panel with standard Laravel conventions or the approved package.
8. Implement public Blade pages from Figma, beginning with Home.
9. Add SEO metadata, canonical URLs, sitemap, robots.txt, accessible media alt text, and cookie consent.
10. Run tests, formatting, and local browser/responsive checks.
11. Commit and push changes to GitHub.
12. Deploy on the server with a controlled pull/install/migrate/cache sequence.
13. Never run remote migrations until the migration diff has been reviewed and the command is explicitly announced.

## Git deployment direction

The intended deployment flow is:

1. Develop locally.
2. Commit and push to `main`.
3. On the server, preserve `.env` and uploads.
4. Pull the approved commit.
5. Run `composer install --no-dev --optimize-autoloader` only after confirming the server Composer environment.
6. Run reviewed migrations with `php artisan migrate --force` only after explicit confirmation.
7. Run `php artisan storage:link` if needed.
8. Cache config/routes/views only after database and application checks pass.
9. Verify `https://first.4bstudio.com.ua/` and the health route.

Never commit real passwords, API keys, tokens, or production `.env` files.

## First Claude Code response

Before making edits, summarize the current `git status`, inspect the existing files, identify any uncommitted changes, and state one small implementation slice plus its focused validation command. Then proceed with the smallest safe change.
