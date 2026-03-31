# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

BÖRD is a PHP imageboard application (4chan-style) built on a custom MVC framework for learning purposes. No build system, no package manager — pure PHP on a LAMP stack.

## Development Environment

The project runs inside a Vagrant VM:

```bash
cd ./Vagrant/LAMP
vagrant up         # Start VM (Apache on localhost:8080)
vagrant ssh        # Shell into VM
vagrant halt       # Stop VM
```

- App served at `http://localhost:8080/bord`
- phpMyAdmin at `http://localhost:8080/phpmyadmin`
- Database schema/seed: `src/BORD.sql` (auto-imported on provisioning)
- DB config: copy `src/config/database.php.example` → `src/config/database.php`

There are no tests, linter, or build commands.

## Architecture

Custom MVC framework with these layers:

**Entry point:** `src/index.php` — initializes DB connection, session, and Router; defines `BASE_URL = '/bord'`

**Router** (`src/app/core/Router.php`) — URL params are named captures (`{id}`) converted to regex and passed as arguments to controller methods.

**Controllers** (`src/app/controllers/`) extend the abstract `Controller` class. Use `$this->render('viewname', $data)` to load templates. Auth state is accessed via `$_SESSION['userid']` and `$_SESSION['role']` (1=user, 2=admin).

**Models** (`src/app/models/`) extend the abstract `Model` class which provides PDO helper methods: `fetch()`, `fetchAll()`, `insert()`, `update()`, `delete()`. Always use these rather than raw PDO to keep queries parameterized.

**Views** (`src/app/views/`) are plain PHP templates. Bootstrap 5 CDN, AMOLED dark theme.

**Routes** are defined in `src/config/routes.php` as closures passed to `$router->addRoute(method, pattern, controller, action)`.

## Key Domain Concepts

- **posts** table uses `parentid` to distinguish threads (null) from replies (set to thread post id)
- `bumptimestamp` on posts is updated when a reply is made, used to sort threads on board pages
- `BoardController::parseContent()` handles greentext (`>text`) and post links (`>>id`) rendering
- File uploads (images, avatars) stored in `src/public/uploads/` with random hash filenames
- Passwords are MD5-hashed (legacy choice, intentional for this learning project)
- Anonymous posting: `userid` on posts can be null; users also have a `postasanon` toggle
