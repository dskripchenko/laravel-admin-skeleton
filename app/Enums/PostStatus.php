<?php

namespace App\Enums;

/**
 * The workflow state of a post. The labels are English source strings,
 * translated through lang/{locale}.json; the tones are the admin badge tones.
 */
enum PostStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Review => 'In review',
            self::Published => 'Published',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Draft => 'default',
            self::Review => 'warning',
            self::Published => 'success',
        };
    }

    /**
     * value => label, for selects and filters.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $s) => [$s->value => $s->label()])->all();
    }

    /**
     * value => [label, tone], for TableColumn::asBadge().
     *
     * @return array<string, array{label: string, tone: string}>
     */
    public static function badges(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $s) => [$s->value => ['label' => $s->label(), 'tone' => $s->tone()]])
            ->all();
    }
}
