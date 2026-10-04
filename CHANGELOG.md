# Changelog

All notable changes to this project are documented in this file.
The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and the project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] — 2026-10-04

### Changed

- Requires `dskripchenko/laravel-admin` `^1.47` and `dskripchenko/laravel-admin-starter` `^1.4.3`.
- The admin registrations and the menu moved from `app/Providers/AdminServiceProvider.php`
  to `app/Admin/AdminPlugin.php`, which is listed in `config('admin.plugins')`. The
  `admin:make-*` wizards update this class, so a generated section is registered and
  added to the menu without manual edits.
- `config/admin.php` is re-synced with laravel-admin 1.47. The API path now defaults to
  the laravel-api prefix, and `AdminLocale` runs first in the API stack, so error
  responses use the panel language.
- `PostResource`: explicit English labels, `singularLabel()`, a status badge with
  labels and colours, a formatted publication date and a labelled status filter.
- Post statuses are now a backed enum, `App\Enums\PostStatus`, and `Post::$status`
  is cast to it.
- `OverviewDashboard`: stat colours and icons that exist in the panel (the drafts card had no
  icon and the in-review card had the wrong colour). Adds a published-share card
  formatted with `precision()`, month labels in the panel locale, a latest-posts list
  with a labelled status badge, and a translated "Next steps" card with a
  working docs link.

### Added

- `lang/ru.json`: Russian translations of the app's admin strings.
- A feature test for the status filter.
- CI: a `create-project` smoke job that installs the skeleton from the branch, signs
  in and checks that the admin shell and the API respond.
- This changelog.

### Fixed

- README: the generators are documented as argument-less wizards that register into
  `AdminPlugin`. The README no longer suggests changing `ADMIN_*` before seeding,
  because `create-project` seeds right away. It now gives the commands that work
  afterwards (`db:seed --class=AdminSeeder`, `admin:user`).

## [1.0.1] - 2026-10-02

### Fixed

- `admin:make-resource` is documented and mentioned on the dashboard as an interactive
  wizard rather than a command that takes a class name.
- Requires `dskripchenko/laravel-admin` `^1.36`.

## [1.0.0] - 2026-10-01

### Added

- A Laravel 13 application with laravel-admin built in: the prebuilt admin SPA at
  `/admin`, users, roles and the audit log from laravel-admin-starter, an example
  `PostResource`, an `OverviewDashboard`, and a super administrator seeded from `.env`.
- `post-create-project-cmd` creates the SQLite database, migrates, publishes the admin
  frontend and seeds. `composer setup` does the same for a clone.
- CI on PHP 8.3–8.5.
- `composer.lock` is not committed, so dependencies resolve for each platform.

[Unreleased]: https://github.com/dskripchenko/laravel-admin-skeleton/compare/v1.0.1...HEAD
[1.0.1]: https://github.com/dskripchenko/laravel-admin-skeleton/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/dskripchenko/laravel-admin-skeleton/releases/tag/v1.0.0
