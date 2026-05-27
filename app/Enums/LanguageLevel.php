<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum LanguageLevel: string implements HasLabel
{
    case Native = 'native';
    case Fluent = 'fluent';
    case Advanced = 'advanced';
    case Intermediate = 'intermediate';
    case Beginner = 'beginner';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Native => 'Ana Dil',
            self::Fluent => 'Akıcı',
            self::Advanced => 'İleri',
            self::Intermediate => 'Orta',
            self::Beginner => 'Başlangıç',
        };
    }
}
