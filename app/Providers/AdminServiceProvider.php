<?php

namespace App\Providers;

use App\Admin\Resources\PostResource;
use App\Admin\Screens\OverviewDashboard;
use Dskripchenko\LaravelAdmin\Facades\Admin;
use Dskripchenko\LaravelAdmin\Menu\MenuNode;
use Illuminate\Support\ServiceProvider;

/**
 * Everything the admin panel shows: resources, screens, dashboards and the
 * menu. Users, roles and the audit log are registered by the starter pack.
 */
class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Admin::resources([PostResource::class]);
        Admin::screen([OverviewDashboard::class]);

        Admin::menu()->withAuto(false)
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
