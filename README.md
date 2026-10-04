# Laravel Admin Skeleton

> 🌐 **English** · [Русский](docs/ru/README.md)

A fresh Laravel application with [dskripchenko/laravel-admin](https://github.com/dskripchenko/laravel-admin)
built in. You can sign in a minute after `composer create-project`:

- an admin panel at `/admin` (a Vue SPA that ships prebuilt, so you don't need Node);
- users, roles and an audit log ([laravel-admin-starter](https://github.com/dskripchenko/laravel-admin-starter));
- an example resource: posts, with a list, a status filter, create/edit with a markdown editor, and a view page;
- an overview dashboard with stats, a chart and the latest records;
- English and Russian, switchable in the panel;
- a super administrator created from `.env`.

## Create a project

```bash
composer create-project dskripchenko/laravel-admin-skeleton my-app
cd my-app
php artisan serve
```

`create-project` creates the SQLite database, runs the migrations, publishes the
admin frontend and seeds an administrator and 30 example posts. Open
http://localhost:8000/admin and sign in as `admin@example.com` / `password`.

The seed has already run by the time you can edit `.env`. To sign in with other
credentials, either change the password in the profile, or set `ADMIN_NAME`,
`ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` and run
`php artisan db:seed --class=AdminSeeder`, which adds that administrator.
To create an administrator directly, use
`php artisan admin:user "Jane Doe" jane@example.com 'a-long-password' --super`.

SQLite is the default database. For MySQL or PostgreSQL, change `DB_*` in `.env`
and run `php artisan migrate --seed`. For Docker:
`composer require laravel/sail --dev && php artisan sail:install`.

To start from a clone instead of `create-project`, run `composer setup`.

## Where things are

| Path | What |
|---|---|
| `app/Admin/AdminPlugin.php` | Registers resources, screens and dashboards, and builds the menu (listed in `config/admin.php` → `plugins`) |
| `app/Admin/Resources/PostResource.php` | The example resource: fields, columns, filters |
| `app/Admin/Screens/OverviewDashboard.php` | The home dashboard |
| `app/Enums/PostStatus.php` | Post statuses with their labels and badge colours |
| `lang/ru.json` | Russian translations of the app's admin strings (labels are English source strings) |
| `config/admin.php` | Admin path, auth, branding, locales |
| `database/seeders/AdminSeeder.php` | The first administrator, from `.env` |

## Adding to the panel

The generators are interactive wizards and take no arguments:

| Command | What it creates |
|---|---|
| `php artisan admin:make-section` | A resource from a table or a model, plus a menu entry and, optionally, a role |
| `php artisan admin:make-resource` | The same resource, with no menu entry and no role |
| `php artisan admin:make-screen` | A custom (non-CRUD) page |
| `php artisan admin:make-widget` | A dashboard widget class; add it to a dashboard's `widgets()` |

They write the class under `app/Admin` and register it in `app/Admin/AdminPlugin.php`.
To put a new section into an existing menu group, answer `content` or `system`
when the wizard asks for the parent menu key. Treat the generated class as a draft:
review its fields, columns and filters before you use it.

To remove the example, delete `PostResource`, `app/Enums/PostStatus.php`, the
`Post` model, its migration and factory, and the related tests in
`tests/Feature/AdminPanelTest.php`. Then remove the posts entries from
`AdminPlugin`, the posts seeding from `DatabaseSeeder`, and the post widgets from
`OverviewDashboard`.

## Updating

`composer update` republishes the admin frontend automatically, because
`php artisan admin:publish` runs in `post-update-cmd`.

## Documentation

See the [laravel-admin docs](https://github.com/dskripchenko/laravel-admin#documentation)
for resources, screens, fields, layouts, actions and permissions.

## License

MIT
