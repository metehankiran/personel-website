<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum EducationDegree: string implements HasLabel
{
    case HighSchool = 'high_school';
    case Associate = 'associate';
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Phd = 'phd';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::HighSchool => 'Lise',
            self::Associate => 'Ön Lisans',
            self::Bachelor => 'Lisans',
            self::Master => 'Yüksek Lisans',
            self::Phd => 'Doktora',
        };
    }
}
