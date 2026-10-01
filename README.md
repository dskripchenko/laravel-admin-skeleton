# Laravel Admin Skeleton

> 🌐 **English** · [Русский](docs/ru/README.md)

A fresh Laravel application with [dskripchenko/laravel-admin](https://github.com/dskripchenko/laravel-admin)
built in — sign in a minute after `composer create-project`:

- an admin panel at `/admin` (Vue SPA, prebuilt — no Node required);
- users, roles and an audit log ([laravel-admin-starter](https://github.com/dskripchenko/laravel-admin-starter));
- an example resource (posts: list, filters, create/edit with a markdown editor, view);
- an overview dashboard with stats, a chart and the latest records;
- a super administrator created from `.env`.

## Create a project

```bash
composer create-project dskripchenko/laravel-admin-skeleton my-app
cd my-app
php artisan serve
```

Open http://localhost:8000/admin and sign in as `admin@example.com` / `password`
(change `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env` before seeding, or the
password in the profile afterwards).

SQLite is the default database; switch `DB_*` in `.env` and run
`php artisan migrate --seed` for MySQL or PostgreSQL. Docker:
`composer require laravel/sail --dev && php artisan sail:install`.

From a clone instead of `create-project`: `composer setup`.

## Where things are

| Path | What |
|---|---|
| `app/Providers/AdminServiceProvider.php` | Registers resources, screens, dashboards and the menu |
| `app/Admin/Resources/PostResource.php` | The example resource — fields, columns, filters |
| `app/Admin/Screens/OverviewDashboard.php` | The home dashboard |
| `config/admin.php` | Admin path, auth, branding, locales |
| `database/seeders/AdminSeeder.php` | The first administrator, from `.env` |

Add a resource: `php artisan admin:make-resource OrderResource`, then register
it in `AdminServiceProvider` and add a menu entry. Remove the example: delete
`PostResource`, the `Post` model, its migration and factory, and its menu entry.

## Updating

`composer update` republishes the admin frontend automatically
(`php artisan admin:publish` in `post-update-cmd`).

## Documentation

[laravel-admin docs](https://github.com/dskripchenko/laravel-admin#documentation) —
resources, screens, fields, layouts, actions, permissions.

## License

MIT
