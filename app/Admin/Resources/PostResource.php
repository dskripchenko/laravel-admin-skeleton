<?php

namespace App\Admin\Resources;

use App\Models\Post;
use Dskripchenko\LaravelAdmin\Field\DatePicker;
use Dskripchenko\LaravelAdmin\Field\Input;
use Dskripchenko\LaravelAdmin\Field\Markdown;
use Dskripchenko\LaravelAdmin\Field\Select;
use Dskripchenko\LaravelAdmin\Field\Slug;
use Dskripchenko\LaravelAdmin\Field\Textarea;
use Dskripchenko\LaravelAdmin\Filter\OptionsFilter;
use Dskripchenko\LaravelAdmin\Resource\Resource;
use Dskripchenko\LaravelAdmin\Table\TableColumn;

/**
 * An example resource — list, create, edit and view pages for App\Models\Post.
 * Delete it (and its menu entry in AdminServiceProvider) when you no longer
 * need it.
 */
final class PostResource extends Resource
{
    public static string $model = Post::class;

    public static string $icon = 'file-text';

    public static function label(): string
    {
        return 'Posts';
    }

    private const STATUSES = ['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published'];

    public function fields(): array
    {
        return [
            Input::make('title')->required(),
            Slug::make('slug')->from('title')->required(),
            Textarea::make('excerpt')->rows(3),
            Markdown::make('body')->preview(),
            Select::make('status')->options(self::STATUSES)->required(),
            DatePicker::make('published_at')->withTime(),
        ];
    }

    public function columns(): array
    {
        return [
            TableColumn::make('id')->sort(),
            TableColumn::make('title')->sort()->search(),
            TableColumn::make('status')->asBadge(['draft' => 'neutral', 'review' => 'warning', 'published' => 'success']),
            TableColumn::make('published_at')->asDateTime()->sort(),
        ];
    }

    public function filters(): array
    {
        return [
            OptionsFilter::for('status')->options(self::STATUSES),
        ];
    }
}
