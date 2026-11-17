# Student Hub — Final Project

A single, responsive student website that brings the earlier exercises and labs together: a personal profile, a working task board, an XML student directory, a native POST contact form, and a playground for PHP/JSON, XMLHttpRequest, and Fetch API demos.

Built by **Mark Louis Pamisa** for the final project — **BS Information Technology, Pamantasan ng Lungsod ng Muntinlupa**.

**Repository:** [github.com/Bulbasur123/final-project-student-hub](https://github.com/Bulbasur123/final-project-student-hub)

---

## What is included

| Part of the site | What it demonstrates |
| --- | --- |
| **Home / Profile** | The personal page from Exercise #1: bio, portrait, course, school, location, and contact links. |
| **TaskFlow** | The task manager from Exercise #2, rebuilt into the final visual system. Add, complete, delete, search, filter, and clear tasks. |
| **XML Directory** | The XML activity data loaded with `XMLHttpRequest`, parsed with `DOMParser`, and rendered as a searchable directory. |
| **Lab Playground** | JSON Labs 1–5: encode, decode, API response, raw JSON input, and an AJAX welcome request. |
| **XHR Playground** | An XMLHttpRequest GET demo with request state and status feedback. |
| **Fetch Playground** | Fetch API GET and POST demos against a same-origin PHP endpoint. |
| **Contact** | The native POST form from Exercise #4, with server-side validation, CSRF protection, and a confirmation page. |

The project is intentionally **solo work** and does not add any group members. It uses **PHP 8+, vanilla HTML/CSS/JavaScript, and JSON file storage** — no framework and no database.

---

## Run it locally

### Option 1 — Windows launcher

Double-click:

```text
start-student-hub.bat
```

The script finds PHP on `PATH` or in a sibling `php83` / `php` folder, starts the built-in PHP server at `http://127.0.0.1:8033/`, and opens the site.

### Option 2 — any terminal with PHP

From the project folder:

```bash
php -S 127.0.0.1:8033 -t .
```

Then open:

```text
http://127.0.0.1:8033/
```

Tested with **PHP 8.3.36** on Windows. PHP 8.0 or newer is recommended.

> The site needs to be served through PHP. Opening `index.php` directly as a file will not run the form handler or the APIs.

---

## First run and storage

- The task board creates `storage/tasks.php` on first load and seeds it with five sample tasks.
- That file contains a PHP guard before the JSON payload, so requesting it over HTTP prints nothing.
- `storage/tasks.php` is ignored by Git. Delete it if you want the sample tasks to be recreated.
- The XML directory reads `data/student_data.xml`; it does not write to it.
- The contact form validates the submission and shows a confirmation page. It does **not** save messages.

---

## Project structure

```text
final-project-student-hub/
├── index.php                 Main one-page Student Hub
├── contact.php               POST-only contact result page
├── api/
│   ├── tasks.php             TaskFlow JSON API
│   └── lab.php               JSON, XHR, and Fetch demo API
├── includes/
│   ├── app.php               Session, CSRF, escaping, JSON helpers
│   └── tasks.php             Task storage and validation helpers
├── assets/
│   ├── fonts/                Self-hosted Fraunces + Manrope font files
│   ├── styles.css            Shared responsive design system
│   ├── app.js                Navigation, scroll state, lab tabs
│   ├── tasks.js              TaskFlow frontend
│   ├── directory.js          XMLHttpRequest + XML rendering
│   ├── labs.js               JSON, XHR, and Fetch demos
│   ├── profile.jpg           Profile portrait
│   └── favicon.svg           Site icon
├── data/
│   └── student_data.xml      Student records for the directory
├── storage/
│   ├── .htaccess             Blocks direct Apache access
│   └── tasks.php             Generated at runtime (ignored by Git)
├── start-student-hub.bat     Local Windows launcher
├── .gitignore
└── README.md
```

---

## API reference

### `api/tasks.php`

| Method | Input | Result |
| --- | --- | --- |
| `GET` | — | Tasks, stats, and a CSRF token. |
| `POST` | `action: add` with `title`, `priority`, `due` | Adds a task. |
| `POST` | `action: toggle` with `id` | Toggles completion. |
| `POST` | `action: delete` with `id` | Deletes one task. |
| `POST` | `action: clear_completed` | Removes all completed tasks. |

POST requests use JSON and require the CSRF token returned by `GET` (sent either as `X-CSRF-Token` or in the JSON `csrf` field).

### `api/lab.php`

| Method | Action | Result |
| --- | --- | --- |
| `GET` | `?action=encode` | Encodes a sample student array as JSON. |
| `GET` | `?action=decode` | Decodes one JSON string as an object and an array. |
| `GET` | `?action=profile` | Returns a sample active profile. |
| `GET` | `?action=post` | Returns a sample post for the XHR/Fetch demos. |
| `POST` | `?action=input` | Reads a raw JSON body and returns its username/password values. |
| `POST` | `?action=welcome` | Reads a JSON name and returns `{"status":"success","message":"Welcome, ...!"}`. |
| `POST` | `?action=post` | Echoes a submitted post with `id: 101` and a timestamp. |

---

## Validation and security notes

- All dynamic output is escaped with `htmlspecialchars` where it is rendered in HTML.
- The contact form checks required fields, email format, allowed roles, and message length on the server. It also verifies a session CSRF token.
- Task writes use a per-session CSRF token and are rejected with HTTP `403` when the token is invalid.
- Task titles are limited to 120 characters and the board is capped at 200 tasks.
- JSON storage writes use an exclusive file lock.
- `storage/tasks.php` starts with `<?php exit; ?>` to prevent its data from being displayed if it is requested directly.
- `storage/.htaccess` denies direct access on Apache deployments.
- The site does not use a database and does not save contact messages.

---

## Manual checks

1. Load the home page; confirm the profile, quick facts, and portrait appear.
2. Add a task, toggle it complete, search for it, delete it, and refresh to confirm server persistence.
3. Type in the directory search; confirm the XML records filter by name, course, or ID.
4. Open each lab tab and run every demo button; responses should appear without reloading.
5. Submit the contact form with a valid message and confirm the result page reports a successful POST.
6. Resize to a mobile width and confirm the navigation menu opens and closes.

---

## Credits

- Design and implementation: Mark Louis Pamisa.
- Fonts: [Fraunces](https://fonts.google.com/specimen/Fraunces) and [Manrope](https://fonts.google.com/specimen/Manrope), self-hosted from Google Fonts under the SIL Open Font License. The license texts are included in `assets/fonts/`.
- Profile photograph: personal asset used with the project.
- Built for the final project requirement and for learning purposes.
