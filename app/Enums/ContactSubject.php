<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ContactSubject: string implements HasLabel
{
    case ProjectInquiry = 'project_inquiry';
    case Collaboration = 'collaboration';
    case Consulting = 'consulting';
    case Support = 'support';
    case Feedback = 'feedback';
    case Other = 'other';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::ProjectInquiry => 'Proje Teklifi',
            self::Collaboration => 'İş Birliği',
            self::Consulting => 'Danışmanlık',
            self::Support => 'Teknik Destek',
            self::Feedback => 'Geri Bildirim',
            self::Other => 'Diğer',
        };
    }
}
