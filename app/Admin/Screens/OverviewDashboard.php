<?php

namespace App\Admin\Screens;

use App\Enums\PostStatus;
use App\Models\Post;
use Dskripchenko\LaravelAdmin\Table\TableColumn;
use Dskripchenko\LaravelAdmin\Widget\ChartWidget;
use Dskripchenko\LaravelAdmin\Widget\DashboardScreen;
use Dskripchenko\LaravelAdmin\Widget\MarkdownWidget;
use Dskripchenko\LaravelAdmin\Widget\RecentListWidget;
use Dskripchenko\LaravelAdmin\Widget\StatsOverviewWidget;
use Illuminate\Support\Carbon;

/**
 * The home dashboard. Widgets are plain PHP; administrators can rearrange,
 * resize and hide them, and the layout is saved per user. Titles and labels
 * are English source strings, translated through lang/{locale}.json.
 */
final class OverviewDashboard extends DashboardScreen
{
    public static function slug(): string
    {
        return 'main';
    }

    public function name(): string
    {
        return 'Overview';
    }

    public function widgets(): array
    {
        $total = Post::count();
        $published = Post::where('status', PostStatus::Published)->count();
        $months = collect(range(5, 0))->map(fn (int $i) => Carbon::now()->startOfMonth()->subMonths($i));

        return [
            StatsOverviewWidget::make()->title('Posts')->size(12)
                ->stat('Total', $total, 'info', 'file-text')
                ->stat('Published', $published, 'success', 'check-circle')
                ->stat('In review', Post::where('status', PostStatus::Review)->count(), 'warning', 'eye')
                // A raw number: the panel formats it by locale — 46.7% / 46,7%.
                ->stat('Published share', $total > 0 ? $published / $total * 100 : 0, 'info', 'percent')
                ->precision(1)->suffix('%'),

            ChartWidget::make()->title('Published per month')->size(6)->chartType('area')
                ->labels($months->map(fn (Carbon $m) => $m->translatedFormat('M'))->all())
                ->dataset('Posts', $months->map(fn (Carbon $m) => Post::where('status', PostStatus::Published)
                    ->whereBetween('published_at', [$m, $m->copy()->endOfMonth()])->count())->all()),

            RecentListWidget::make()->title('Latest posts')->size(6)
                ->model(Post::class)->orderBy('created_at', 'desc')->limit(6)
                ->column('title', 'Title')
                ->column(TableColumn::make('status')->label('Status')->asBadge(PostStatus::badges()))
                ->linkTo('posts'),

            MarkdownWidget::make()->title('Next steps')->size(12)->content(fn (): string => implode("\n", array_map(
                fn (string $line): string => '- '.__($line),
                [
                    'Your admin code lives in `app/Admin` and is registered in `app/Admin/AdminPlugin.php`.',
                    'Add a section — a resource, its menu entry and an optional role — with `php artisan admin:make-section`.',
                    'Users, roles and the audit log come from `dskripchenko/laravel-admin-starter`.',
                    'Documentation: [github.com/dskripchenko/laravel-admin](https://github.com/dskripchenko/laravel-admin#documentation).',
                ],
            ))),
        ];
    }
}
