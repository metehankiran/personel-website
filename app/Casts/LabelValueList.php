<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * An ordered list of label and value pairs kept in a json column.
 *
 * Rows written by the old key value panel field hold a {"label": "value"} map instead;
 * those are read as pairs too, and are stored as a list the next time they are saved.
 *
 * @implements CastsAttributes<list<array{label: string, value: string}>|null, array<array-key, mixed>|null>
 */
class LabelValueList implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     * @return list<array{label: string, value: string}>|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        return $value === null ? null : $this->pairs(json_decode($value, true) ?? []);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === null ? null : json_encode($this->pairs($value), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param  array<array-key, mixed>  $items
     * @return list<array{label: string, value: string}>
     */
    private function pairs(array $items): array
    {
        if (! array_is_list($items) && ! is_array(reset($items))) {
            $items = array_map(fn (mixed $value, int|string $label): array => ['label' => $label, 'value' => $value], $items, array_keys($items));
        }

        return array_map(fn (array $item): array => [
            'label' => (string) ($item['label'] ?? ''),
            'value' => (string) ($item['value'] ?? ''),
        ], array_values($items));
    }
}
