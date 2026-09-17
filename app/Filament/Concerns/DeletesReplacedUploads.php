<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Settings are not Eloquent models, so Filament never cleans up files that a
 * settings page replaces or clears. Pages list their upload fields and the
 * stale files are removed from the public disk once the save has succeeded.
 */
trait DeletesReplacedUploads
{
    /** @var array<int, string> */
    protected array $staleUploads = [];

    /**
     * @return array<int, string>
     */
    abstract protected function uploadFields(): array;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $settings = app(static::getSettings());

        foreach ($this->uploadFields() as $field) {
            $previous = $settings->{$field};

            if (filled($previous) && $previous !== ($data[$field] ?? null)) {
                $this->staleUploads[] = $previous;
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        Storage::disk('public')->delete($this->staleUploads);

        $this->staleUploads = [];
    }
}
