# Laravel Admin Skeleton

> 🌐 [English](../../README.md) · **Русский**

Свежее Laravel-приложение со встроенной [dskripchenko/laravel-admin](https://github.com/dskripchenko/laravel-admin) —
вход в админку через минуту после `composer create-project`:

- админ-панель на `/admin` (Vue SPA, уже собрана — Node не нужен);
- пользователи, роли и журнал аудита ([laravel-admin-starter](https://github.com/dskripchenko/laravel-admin-starter));
- пример ресурса (посты: список, фильтры, создание и редактирование с markdown-редактором, просмотр);
- обзорный дашборд со статистикой, графиком и последними записями;
- супер-администратор, созданный из `.env`.

## Создать проект

```bash
composer create-project dskripchenko/laravel-admin-skeleton my-app
cd my-app
php artisan serve
```

Откройте http://localhost:8000/admin и войдите как `admin@example.com` / `password`
(поменяйте `ADMIN_EMAIL` / `ADMIN_PASSWORD` в `.env` до сида или пароль в
профиле после).

По умолчанию база — SQLite; для MySQL или PostgreSQL поменяйте `DB_*` в `.env` и
выполните `php artisan migrate --seed`. Docker:
`composer require laravel/sail --dev && php artisan sail:install`.

Из клона репозитория вместо `create-project`: `composer setup`.

## Где что лежит

| Путь | Что |
|---|---|
| `app/Providers/AdminServiceProvider.php` | Регистрация ресурсов, экранов, дашбордов и меню |
| `app/Admin/Resources/PostResource.php` | Пример ресурса — поля, колонки, фильтры |
| `app/Admin/Screens/OverviewDashboard.php` | Главный дашборд |
| `config/admin.php` | Путь админки, авторизация, брендинг, языки |
| `database/seeders/AdminSeeder.php` | Первый администратор из `.env` |

Новый ресурс: `php artisan admin:make-resource OrderResource`, затем
зарегистрируйте его в `AdminServiceProvider` и добавьте пункт меню. Убрать
пример: удалите `PostResource`, модель `Post`, её миграцию и фабрику и пункт меню.

## Обновление

`composer update` сам публикует фронтенд админки заново
(`php artisan admin:publish` в `post-update-cmd`).

## Документация

[Документация laravel-admin](https://github.com/dskripchenko/laravel-admin#documentation) —
ресурсы, экраны, поля, раскладки, действия, права.

## Лицензия

MIT
