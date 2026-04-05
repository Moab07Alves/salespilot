<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(fn ($model) => $model->generateSlug());

        static::updating(function ($model) {
            if ($model->shouldRegenerateSlug()) {
                $model->generateSlug();
            }
        });

        static::restoring(fn ($model) => $model->handleSlugOnRestore());
    }

    protected function generateSlug(): void
    {
        $slugField = $this->getSlugField();

        // Não sobrescreve slug manual
        if (!empty($this->{$slugField}) && !$this->isDirty($slugField)) {
            return;
        }

        $baseSlug = $this->buildBaseSlug();

        $this->{$slugField} = $this->resolveUniqueSlug($baseSlug);
    }

    protected function buildBaseSlug(): string
    {
        $parts = [];

        foreach ($this->getSlugSource() as $field) {
            $value = data_get($this, $field);

            if (!empty($value)) {
                $parts[] = Str::slug($value);
            }
        }

        $slug = implode('-', $parts);

        return $slug !== '' ? $slug : Str::random(8);
    }

    protected function resolveUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $slug): bool
    {
        return static::withTrashed()
            ->where($this->getSlugField(), $slug)
            ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
            ->exists();
    }

    protected function shouldRegenerateSlug(): bool
    {
        return $this->isDirty($this->getSlugSource());
    }

    protected function handleSlugOnRestore(): void
    {
        $slugField = $this->getSlugField();
        $currentSlug = $this->{$slugField};

        $exists = static::query()
            ->where($slugField, $currentSlug)
            ->whereNull('deleted_at')
            ->whereKeyNot($this->getKey())
            ->exists();

        if ($exists) {
            $this->{$slugField} = $this->resolveUniqueSlug(Str::slug($currentSlug));
        }
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
