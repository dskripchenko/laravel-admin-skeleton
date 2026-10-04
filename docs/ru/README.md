# Laravel Admin Skeleton

> 🌐 [English](../../README.md) · **Русский**

Свежее Laravel-приложение со встроенной [dskripchenko/laravel-admin](https://github.com/dskripchenko/laravel-admin).
Войти в админку можно через минуту после `composer create-project`:

- админ-панель на `/admin` (Vue SPA поставляется уже собранной, Node не нужен);
- пользователи, роли и журнал аудита ([laravel-admin-starter](https://github.com/dskripchenko/laravel-admin-starter));
- пример ресурса — посты: список, фильтр по статусу, создание и редактирование с markdown-редактором, просмотр;
- обзорный дашборд со статистикой, графиком и последними записями;
- английский и русский языки, переключаются в панели;
- супер-администратор, созданный из `.env`.

## Создать проект

```bash
composer create-project dskripchenko/laravel-admin-skeleton my-app
cd my-app
php artisan serve
```

`create-project` создаёт базу SQLite, выполняет миграции, публикует фронтенд
админки и засевает администратора и 30 постов-примеров. Откройте
http://localhost:8000/admin и войдите как `admin@example.com` / `password`.

Сид выполняется раньше, чем вы успеете отредактировать `.env`. Чтобы входить с
другими данными, смените пароль в профиле — или задайте `ADMIN_NAME`,
`ADMIN_EMAIL` и `ADMIN_PASSWORD` в `.env` и выполните
`php artisan db:seed --class=AdminSeeder`: сид добавит этого администратора.
Создать администратора можно и напрямую:
`php artisan admin:user "Jane Doe" jane@example.com 'a-long-password' --super`.

По умолчанию база — SQLite. Для MySQL или PostgreSQL поменяйте `DB_*` в `.env` и
выполните `php artisan migrate --seed`. Для Docker:
`composer require laravel/sail --dev && php artisan sail:install`.

Если вы начинаете с клона репозитория, а не с `create-project`, выполните `composer setup`.

## Где что лежит

| Путь | Что |
|---|---|
| `app/Admin/AdminPlugin.php` | Регистрация ресурсов, экранов и дашбордов, меню (подключён в `config/admin.php` → `plugins`) |
| `app/Admin/Resources/PostResource.php` | Пример ресурса: поля, колонки, фильтры |
| `app/Admin/Screens/OverviewDashboard.php` | Главный дашборд |
| `app/Enums/PostStatus.php` | Статусы поста с подписями и цветами бейджей |
| `lang/ru.json` | Русские переводы строк админки приложения (исходные подписи — английские) |
| `config/admin.php` | Путь админки, авторизация, брендинг, языки |
| `database/seeders/AdminSeeder.php` | Первый администратор из `.env` |

## Что добавить в панель

Генераторы — интерактивные мастера, аргументов они не принимают:

| Команда | Что создаёт |
|---|---|
| `php artisan admin:make-section` | Ресурс по таблице или модели, пункт меню и, по желанию, роль |
| `php artisan admin:make-resource` | Тот же ресурс, без пункта меню и роли |
| `php artisan admin:make-screen` | Произвольную страницу, не CRUD |
| `php artisan admin:make-widget` | Класс виджета дашборда; добавьте его в `widgets()` нужного дашборда |

Мастера кладут класс в `app/Admin` и регистрируют его в `app/Admin/AdminPlugin.php`.
Чтобы новый раздел попал в существующую группу меню, на вопрос о родительском
ключе меню ответьте `content` или `system`. Сгенерированный класс — черновик:
проверьте его поля, колонки и фильтры, прежде чем им пользоваться.

Чтобы убрать пример, удалите `PostResource`, `app/Enums/PostStatus.php`, модель
`Post`, её миграцию и фабрику и связанные тесты в `tests/Feature/AdminPanelTest.php`.
Затем уберите записи о постах из `AdminPlugin`, засев постов из `DatabaseSeeder`
и виджеты постов из `OverviewDashboard`.

## Обновление

`composer update` сам публикует фронтенд админки заново: в `post-update-cmd`
выполняется `php artisan admin:publish`.

## Документация

[Документация laravel-admin](https://github.com/dskripchenko/laravel-admin#documentation)
описывает ресурсы, экраны, поля, раскладки, действия и права.

## Лицензия

MIT
