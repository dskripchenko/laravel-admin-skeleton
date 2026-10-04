<?php

namespace App\Admin\Resources;

use App\Enums\PostStatus;
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
 * Delete it (and its entries in app/Admin/AdminPlugin.php) when you no longer
 * need it.
 *
 * Labels are English source strings; lang/ru.json translates them.
 */
final class PostResource extends Resource
{
    public static string $model = Post::class;

    public static string $icon = 'file-text';

    public static function label(): string
    {
        return 'Posts';
    }

    /** One record, as it reads mid-sentence: "Create post", "Delete post …?". */
    public static function singularLabel(): string
    {
        return 'post';
    }

    public function fields(): array
    {
        return [
            Input::make('title')->title('Title')->required(),
            Slug::make('slug')->title('Slug')->from('title')->required(),
            Textarea::make('excerpt')->title('Excerpt')->rows(3),
            Markdown::make('body')->title('Body')->preview(),
            Select::make('status')->title('Status')->options(PostStatus::options())->required(),
            DatePicker::make('published_at')->title('Published at')->withTime(),
        ];
    }

    public function columns(): array
    {
        return [
            TableColumn::make('id')->label('ID')->sort(),
            TableColumn::make('title')->label('Title')->sort()->search(),
            TableColumn::make('status')->label('Status')->asBadge(PostStatus::badges()),
            TableColumn::make('published_at')->label('Published at')->asDateTime('Y-m-d H:i')->sort(),
        ];
    }

    public function filters(): array
    {
        return [
            OptionsFilter::for('status')->label('Status')->options(PostStatus::options()),
        ];
    }
}
