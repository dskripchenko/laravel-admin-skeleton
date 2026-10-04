<?php

namespace App\Admin;

use App\Admin\Resources\PostResource;
use App\Admin\Screens\OverviewDashboard;
use Dskripchenko\LaravelAdmin\Admin;
use Dskripchenko\LaravelAdmin\Menu\MenuNode;
use Dskripchenko\LaravelAdmin\Plugin\AdminPlugin as AdminPluginContract;

/**
 * Everything the admin panel shows: resources, screens, dashboards and the
 * menu. Listed in config('admin.plugins'); users, roles and the audit log are
 * registered by the starter pack.
 *
 * The admin:make-section, admin:make-resource, admin:make-screen and
 * admin:make-widget wizards add what they generate to this class.
 */
final class AdminPlugin implements AdminPluginContract
{
    public function name(): string
    {
        return 'app';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function register(): void
    {
        // Custom permissions and settings are registered here.
    }

    public function boot(Admin $admin): void
    {
        $admin->resources([
            PostResource::class,
        ]);
        $admin->screen([
            OverviewDashboard::class,
        ]);

        $admin->menu()->withAuto(false)
            ->add(MenuNode::dashboard('main')->label('Overview')->icon('layout-dashboard'))
            ->add(MenuNode::make('content', 'Content')->icon('book-open')->children([
                MenuNode::resource('posts'),
            ]))
            ->add(MenuNode::make('system', 'System')->icon('settings')->children([
                MenuNode::resource('system-users'),
                MenuNode::resource('system-roles'),
                MenuNode::resource('system-audit'),
            ]));
    }
}
