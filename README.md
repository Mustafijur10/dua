# Noor — Dua Daily

Noor is a single-admin Islamic content library and daily dashboard built with Laravel 11/12, Vue 3, Vite and MySQL. The public site is English/Bangla bilingual. Its content is managed from the authenticated `/admin` area.

## Requirements

- PHP 8.2+ with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json` and `fileinfo`
- Composer 2
- Node.js 20+ and npm
- MySQL 8+

## Install and run

1. Create a MySQL database named `dua_daily`.
2. From the project directory, install dependencies and create the environment file:

   ```sh
   cp .env.example .env
   composer install
   php artisan key:generate
   npm install
   ```

3. Set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` in `.env`. The supplied example uses file-backed sessions and `Asia/Dhaka` as the application timezone; change `APP_TIMEZONE` if your daily calendar should follow another timezone.
4. Create the tables, starter categories and admin account, then build the frontend:

   ```sh
   php artisan migrate --seed
   npm run build
   ```

5. Start the application:

   ```sh
   php artisan serve
   ```

   Public website: <http://127.0.0.1:8000/>  
   Admin dashboard: <http://127.0.0.1:8000/admin/>  
   Admin dua library: <http://127.0.0.1:8000/admin/dua>  
   Admin stories and carousel schedule: <http://127.0.0.1:8000/admin/stories>  
   Admin masael: <http://127.0.0.1:8000/admin/masael>  
   Admin resource shelf: <http://127.0.0.1:8000/admin/resources>  
   Admin notes: <http://127.0.0.1:8000/admin/notes>  
   Admin categories: <http://127.0.0.1:8000/admin/categories>

For frontend development with hot reload, run `npm run dev` in a second terminal.

## Starter admin account

- **Email:** `admin@noor.local`
- **Password:** `NoorDaily!2026`

The seeder creates this account only if it does not already exist. Change the password before exposing the application to the internet. There is no public registration route.

## SQL import

`database/dua_daily.sql` includes the core and new content tables and uses `CREATE TABLE IF NOT EXISTS`. Choose either the migration flow above or import this file into the `dua_daily` database. If you import SQL first, configure `.env` and run `php artisan migrate --seed`; the legacy base migration now skips tables that already exist, and the new module migration also guards existing tables. Alternatively, after manual SQL import, run `php artisan db:seed` to add the starter account and categories.

## Public features

- Four rotating daily carousel slides: a numbered Qur’an ayah, Arabic and translated Sahih hadith with linked context notes, a scheduled biography/story, and a rotating Islamic resource.
- When no resources have been published by the administrator, the carousel uses the daily One Islam Productions feed when available and a pair of trusted reference sites as a fallback. The server needs outbound HTTPS access for the live channel feed.
- Public story collection with filters for Prophets, Sahaba, Tabi‘un, Atba‘ al-Tabi‘in, women of Islam and scholars. Story detail pages show the short account, historical context, life journey, full story, lessons and source links when provided.
- Public masael questions and sourced answers, plus a resource shelf for Islamic links, videos, papers, journals, books and scholar lectures.
- English/Bangla language selector; the preference is saved in the visitor’s browser.
- Public dua library, collections and admin-created notes.
- Compact monthly calendar and personal dashboard widgets in the admin workspace.

## Admin content workflow

- **Stories:** enter the title, story group, person, era, short and full story, life journey, context/importance, lessons, cover image URL and source URLs. Unpublished drafts remain private.
- **Carousel schedule:** select a story and first date; choose one-time, daily, weekly, monthly or yearly recurrence; optionally set an end date, priority and active status. If no schedule matches a day, a published story is selected on a deterministic daily rotation.
- **Resources:** add the title, type, author/scholar, original URL, cover URL, publication date and summary. Publish it to the resource shelf and optionally mark it for the carousel rotation.
- **Masael:** add a question, short answer, detailed response, category and references. Only published entries appear publicly.
- **Duas and notes:** add and edit dua text, categories/subcategories, references and private/admin notes. Notes published by the admin are shown on the public notes page.

Religious and historical content should be checked against reliable primary sources and reviewed by a qualified scholar where appropriate. The app provides structured fields and links; it does not independently issue religious rulings.

## Security and scope

This starter is intended for one administrator. Admin write APIs use Laravel session authentication and CSRF protection. Deploy behind HTTPS, set a strong password and keep Laravel’s writable `storage` and `bootstrap/cache` directories available. The calendar is a visual monthly calendar, not a prayer-time or task scheduler. Prayer notifications, Hijri-date calculation and multi-user ownership are not included.
