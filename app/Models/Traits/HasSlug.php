<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::saving(function ($model) {

            $slugField = $model->getSlugField();
            $slug = $model->buildSlug();

            if (!$model->isDirty($slugField) && $model->{$slugField}) {
                return;
            }

            $model->{$slugField} = $slug;
        });
    }

    protected function buildSlug(): string
    {
        $parts = [];

        foreach ($this->getSlugSource() as $field) {
            $value = data_get($this, $field);

            if ($value) {
                $parts[] = Str::slug($value);
            }
        }

        $baseSlug = implode('-', array_filter($parts));

        if ($baseSlug === '') {
            $baseSlug = Str::random(8);
        }

        $cacheKey = $this->getSlugCacheKey($baseSlug);

        return Cache::rememberForever($cacheKey, function () use ($baseSlug) {
            return $this->resolveUniqueSlug($baseSlug);
        });
    }

    protected function resolveUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug;

        if (!$this->slugExists($slug)) {
            return $slug;
        }

        $counter = 1;

        do {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        } while ($this->slugExists($slug));

        return $slug;
    }

    protected function slugExists(string $slug): bool
    {
        return static::query()
            ->where($this->getSlugField(), $slug)
            ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
            ->exists();
    }

    protected function getSlugCacheKey(string $baseSlug): string
    {
        return sprintf(
            'slug:%s:%s',
            static::class,
            $baseSlug
        );
    }

    public function getSlugField(): string
    {
        return 'slug';
    }

    public function getSlugSource(): array
    {
        return ['name'];
    }
}
