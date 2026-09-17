<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(): JsonResponse
    {
        $items = collect()
            ->merge($this->staticPages())
            ->merge($this->dynamicPages())
            ->merge($this->projects())
            ->merge($this->posts())
            ->merge($this->quickAccess());

        return response()->json($items->values());
    }

    private function staticPages(): Collection
    {
        return collect([
            ['type' => 'Sayfa', 'title' => 'Ana sayfa', 'desc' => 'Hero, öne çıkan proje ve özet', 'url' => route('home'), 'icon' => 'home'],
            ['type' => 'Sayfa', 'title' => 'Hakkımda', 'desc' => 'Biyografi, zaman çizelgesi', 'url' => route('about'), 'icon' => 'user'],
            ['type' => 'Sayfa', 'title' => 'Projeler', 'desc' => 'Tüm freelance işler', 'url' => route('projects'), 'icon' => 'folder-open'],
            ['type' => 'Sayfa', 'title' => 'Hizmetler', 'desc' => 'Çalışma şekilleri ve fiyatlandırma', 'url' => route('services'), 'icon' => 'handshake'],
            ['type' => 'Sayfa', 'title' => 'Teknolojiler', 'desc' => 'Kullandığım araçlar ve deneyim sürem', 'url' => route('stack'), 'icon' => 'layers'],
            ['type' => 'Sayfa', 'title' => 'Referanslar', 'desc' => 'Müşteri yorumları ve markalar', 'url' => route('references'), 'icon' => 'quote'],
            ['type' => 'Sayfa', 'title' => 'Blog', 'desc' => 'Teknik makaleler', 'url' => route('blog'), 'icon' => 'notebook-pen'],
            ['type' => 'Sayfa', 'title' => 'Yer İşaretleri', 'desc' => 'Faydalı linkler', 'url' => route('bookmarks'), 'icon' => 'bookmark'],
            ['type' => 'Sayfa', 'title' => 'CV', 'desc' => 'Özgeçmiş, PDF indir', 'url' => route('cv'), 'icon' => 'file-text'],
            ['type' => 'Sayfa', 'title' => 'İletişim', 'desc' => 'Email, sosyal, form', 'url' => route('contact'), 'icon' => 'mail'],
        ])->when(Faq::published()->exists(), fn (Collection $pages): Collection => $pages->push(
            ['type' => 'Sayfa', 'title' => 'Sıkça Sorulan Sorular', 'desc' => 'Fiyat, süre ve süreçle ilgili yanıtlar', 'url' => route('faq'), 'icon' => 'circle-help'],
        ));
    }

    private function dynamicPages(): Collection
    {
        return Page::published()->get()->map(fn (Page $page) => [
            'type' => 'Sayfa',
            'title' => $page->title,
            'desc' => Str::limit(strip_tags($page->body ?? ''), 80),
            'url' => route('pages.show', $page->slug),
            'icon' => 'file',
        ]);
    }

    private function projects(): Collection
    {
        return Project::ordered()->get()->map(fn (Project $project) => [
            'type' => 'Proje',
            'title' => $project->title,
            'desc' => Str::limit($project->description ?? '', 80),
            'url' => route('projects.show', $project->slug),
            'icon' => 'folder-open',
        ]);
    }

    private function posts(): Collection
    {
        return Post::published()->latest('published_at')->get()->map(fn (Post $post) => [
            'type' => 'Yazı',
            'title' => $post->title,
            'desc' => Str::limit($post->excerpt ?? '', 80),
            'url' => route('blog.show', $post->slug),
            'icon' => 'pen-line',
        ]);
    }

    private function quickAccess(): Collection
    {
        $general = app(GeneralSettings::class);
        $social = app(SocialSettings::class);

        $items = collect();

        if ($general->author_email) {
            $items->push(['type' => 'Hızlı erişim', 'title' => 'Email gönder', 'desc' => $general->author_email, 'url' => 'mailto:'.$general->author_email, 'icon' => 'send']);
        }

        if ($general->cv_path) {
            $items->push(['type' => 'Hızlı erişim', 'title' => 'CV indir', 'desc' => 'PDF olarak özgeçmiş', 'url' => Storage::url($general->cv_path), 'icon' => 'download']);
        }

        $socials = [
            ['url' => $social->github_url, 'title' => 'GitHub'],
            ['url' => $social->linkedin_url, 'title' => 'LinkedIn'],
            ['url' => $social->twitter_url, 'title' => 'Twitter / X'],
            ['url' => $social->youtube_url, 'title' => 'YouTube'],
            ['url' => $social->instagram_url, 'title' => 'Instagram'],
            ['url' => $social->bluesky_url, 'title' => 'Bluesky'],
        ];

        foreach ($socials as $link) {
            if ($link['url']) {
                $items->push([
                    'type' => 'Hızlı erişim',
                    'title' => $link['title'],
                    'desc' => str_replace('https://', '', $link['url']),
                    'url' => $link['url'],
                    'icon' => 'external-link',
                ]);
            }
        }

        $items->push(['type' => 'Hızlı erişim', 'title' => 'Tema değiştir', 'desc' => 'Açık ↔ koyu mod', 'url' => '#toggle-theme', 'icon' => 'sun-moon']);

        return $items;
    }
}
