# AlphaCMS Engine (`www`)

AlphaCMS is a modular PHP CMS/social-platform engine focused on community features, user-generated content, and classic web-hosting compatibility.  
The codebase includes a built-in installer, modular feature packages, and an admin panel for configuration and moderation.

## What This Engine Provides

- User accounts, profiles, and authentication flows
- Content modules: blogs, forum, communities, photos, videos, music, files
- Comments, likes, subscriptions, notifications, private interactions
- Admin panel for site settings, access control, moderation, and logs
- Built-in multilingual foundations (`system/languages`)
- Theme/version-based frontend layouts (`style/version`)

## Tech Overview

- **Language:** PHP (legacy architecture, procedural + utility classes)
- **Database:** MySQL/MariaDB (schema scripts in `install/tables`)
- **Runtime pattern:** central bootstrap via `system/connections/*`
- **Autoloading:** simple class autoload from `system/PHP-classes`
- **Configuration:** INI-based settings (`system/config/global/settings.ini`)

## Minimum Requirements

- PHP **7.2** or **7.3** (installer enforces this range)
- MySQL/MariaDB database
- Web server with PHP support (Apache/Nginx + PHP-FPM, shared hosting, etc.)
- Write permissions for upload/runtime directories as required by installer

## Installation

1. Upload project files to your web root.
2. Create an empty MySQL/MariaDB database.
3. Open `https://your-domain/install/`.
4. Complete installer steps (DB credentials, base settings, tables import).
5. Remove or restrict `/install/` after successful setup.

> The engine redirects to `/install/` when installation is not completed.

## Project Structure

- `main/` - homepage entry and main page components
- `modules/` - feature modules (forum, blogs, media, communities, etc.)
- `users/` - user account, settings, and personal sections
- `panel/` - administration and moderation interface
- `system/` - core bootstrap, config loading, functions, classes, language
- `install/` - web installer and SQL schema files
- `files/` - uploaded/static file handling and receivers
- `style/` - frontend themes and layout variants

## Core Flow (High Level)

1. Global config is loaded from `system/connections/global/config.php`.
2. Core connections initialize session, constants, DB settings, and globals.
3. Function libraries and classes are loaded from `system/functions` and `system/PHP-classes`.
4. Page handlers render through header/footer wrappers (`acms_header`, `acms_footer`).
5. Modules are included conditionally based on configuration and routing parameters.

## Security Notes for Production

- Disable verbose interpreter error output in production (`INTERPRETATOR=0`).
- Protect `system/config/*` from public access.
- Restrict executable permissions in upload directories.
- Enforce HTTPS and secure cookie/session settings at server level.
- Keep backups of DB and uploaded files.

## Maintenance Tips

- Keep changes modular: prefer editing module/plugin files over core bootstrap files.
- Document customizations to simplify future merges and updates.
- Validate permissions after deployment and after hosting migrations.

## License and Support

This repository contains the AlphaCMS engine source used by the project.  
For project-specific legal terms, support contacts, and distribution policy, use your internal or official project documentation.
