# CampusConnect

A student networking web application built with HTML, CSS, JavaScript, PHP, MySQL, AJAX and XML.

## Features in this version
- Student registration with password hashing and login sessions
- Student profile viewing and profile editing
- Campus feed: create posts, delete your own posts, like/unlike and comment
- Search students by name, college, branch or skills
- Create and browse student projects
- Basic notifications for likes and comments
- XML export of projects
- PDO prepared statements, foreign keys, unique constraints and CSRF tokens

## Requirements
- XAMPP (Apache + MySQL)
- PHP 8+ recommended
- MySQL/MariaDB with PDO MySQL and XMLWriter enabled

## Run locally
1. Copy the `CampusConnect` folder into `C:\\xampp\\htdocs\\`.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Import `database/campusconnect.sql`.
5. Confirm database credentials in `config.php` (default XAMPP root with blank password).
6. Open `http://localhost/CampusConnect/`.

## AJAX and XML
- `assets/js/ajax.js` submits signup using `XMLHttpRequest`.
- `assets/js/main.js` demonstrates the Fetch API by retrieving the XML export.
- Open `http://localhost/CampusConnect/xml/export.php` to view the project XML.

## Main file map
- `config.php`: database connection and session startup.
- `includes/functions.php`: shared helpers, login guard, escaping, CSRF and flash messages.
- `includes/header.php`, `includes/footer.php`: shared page layout.
- `api.php`: POST actions and authentication endpoints, selected by `?action=...`.
- `database/campusconnect.sql`: database and table definitions.
- `assets/css/style.css`: responsive UI.
- `assets/js/ajax.js`, `assets/js/main.js`: AJAX examples.
- `xml/export.php`: database-to-XML export.

## Important scope notes
This is a college-project foundation, not a production-ready LinkedIn clone. The connection action currently records a pending request but this version does not yet provide accept/reject screens. The opportunities table is created but there is no opportunities management UI yet. Profile photos, post image uploads, password reset, pagination, moderation, and admin roles are not included. Add HTTPS, stricter URL validation, rate limiting, upload validation and deployment secrets before public deployment.

