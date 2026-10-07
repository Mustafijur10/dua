# Noor — Dua Daily
A calm, responsive personal dua library and daily dashboard built with Laravel 11/12, Vue 3, Vite, and MySQL.

## Requirements
PHP 8.2+ with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`; Composer 2; Node.js 20+ / npm; MySQL 8+.

## Run locally
1. Create an empty MySQL database called `dua_daily`.
2. In this project directory, run:
```sh
cp .env.example .env
composer install
php artisan key:generate
npm install
```
3. Set `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` to your local MySQL credentials. The supplied example uses `SESSION_DRIVER=file` for an easy local start.
4. Create tables and starter admin/categories, then build assets:
```sh
php artisan migrate --seed
npm run build
```
5. Start Laravel:
```sh
php artisan serve
```
Open the public website at http://127.0.0.1:8000.
Admin dashboard: http://127.0.0.1:8000/admin
Manage duas: http://127.0.0.1:8000/admin/dua
Manage notes: http://127.0.0.1:8000/admin/notes
Manage collections: http://127.0.0.1:8000/admin/categories

For live frontend development, use a second terminal and run `npm run dev` instead of `npm run build`.

## Starter admin login
**Email / username:** `admin@noor.local`  
**Password:** `NoorDaily!2026`

Change the seeded password immediately for any internet-accessible deployment. The seeded account is created only if that email does not already exist. There is no public registration route.

## SQL import alternative
`database/dua_daily.sql` contains the MySQL schema. For the application install, prefer `php artisan migrate --seed` so Laravel tracks migrations and the initial admin/categories are added. To import the schema manually, create the `dua_daily` database and import the SQL file, configure `.env`, then run `php artisan db:seed` to create the starter admin and categories.

## Included
- Public home with a timed three-slide carousel (daily ayah, Sahih hadith, and a current One Islam Productions video), including numbered Quran references, source-linked verse/hadith context, and bilingual Bangla/English display; dua library, collections, and notes at `/`, `/duas`, `/categories`, and `/notes`.
- The featured video is selected daily from One Islam Productions’ public YouTube uploads feed and cached for two hours. If the feed cannot be reached, five curated videos are used as a fallback. The server needs outbound HTTPS access for live uploads.
- Public interface language switcher for English and Bangla; the selection is saved in the visitor browser.
- Admin dashboard and management pages at `/admin`, `/admin/dua`, `/admin/notes`, and `/admin/categories`, with an obvious sign-out button.
- Admin-created notes are displayed on the public Notes page.
- Dua create/edit/delete with Arabic, transliteration, translation, reference, personal note, category/subcategory, search, and category filter.
- Categories/collections with starter categories.
- Create/edit/pin/delete personal notes.
- Session-based admin sign-in and protected write APIs; read-only first-load dua library.
- MySQL migration and importable SQL schema.

## Notes
The calendar is a presentational monthly calendar, not a task scheduler. Prayer times, Hijri dates, notifications, and multi-user ownership are not included. This starter is intended for a single administrator and should be deployed behind HTTPS with a changed password.
