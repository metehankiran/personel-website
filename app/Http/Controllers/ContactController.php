<?php

namespace App\Http\Controllers;

use App\Enums\ContactSubject;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', Rule::enum(ContactSubject::class)],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Contact::create($validated);

        return back()->with('success', 'Mesajınız başarıyla gönderildi.');
    }
}
