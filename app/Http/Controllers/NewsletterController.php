<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $validated['email']],
        );

        if (! $subscriber->is_active) {
            $subscriber->update(['is_active' => true]);
        }

        return back()->with('success', 'Bültenimize başarıyla abone oldunuz!');
    }

    public function unsubscribe(string $email, string $token): RedirectResponse
    {
        $subscriber = Subscriber::where('email', $email)->first();

        if (! $subscriber || $subscriber->token !== $token) {
            return redirect()->route('home')->with('error', 'Geçersiz abonelik bağlantısı.');
        }

        $subscriber->update(['is_active' => false]);

        return redirect()->route('home')->with('success', 'Aboneliğiniz başarıyla iptal edildi.');
    }
}
