<?php

namespace App\Models;

use App\Enums\ContactSubject;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'subject' => ContactSubject::class,
            'is_read' => 'boolean',
        ];
    }
}
