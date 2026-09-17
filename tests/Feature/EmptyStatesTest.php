<?php

declare(strict_types=1);

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;

uses(RefreshDatabase::class);

it('renders an empty state with a title, an optional description and an optional action', function () {
    $html = Blade::render('<x-empty-state icon="notebook-pen" title="Henüz bir yazı yayınlanmadı" description="İlk yazı yolda." action-label="Bültene katıl" action-url="/blog" />');

    expect($html)->toContain('data-empty-state')
        ->toContain('data-lucide="notebook-pen"')
        ->toContain('Henüz bir yazı yayınlanmadı')
        ->toContain('İlk yazı yolda.')
        ->toContain('href="/blog"')
        ->toContain('Bültene katıl');

    expect(Blade::render('<x-empty-state title="Boş" />'))->toContain('Boş')->not->toContain('<a ');
});

describe('home page', function () {
    it('keeps the blog section and says so when nothing is published', function () {
        Post::factory()->for(Category::factory())->create(['title' => 'Taslak Yazı', 'is_published' => false]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Son Yazılar', 'Henüz bir yazı yayınlanmadı'])
            ->assertDontSee('Taslak Yazı');
    });

    it('shows the posts and no empty state once something is published', function () {
        Post::factory()->published()->for(Category::factory())->create(['title' => 'İlk Yazı']);

        $this->get(route('home'))->assertSee('İlk Yazı')->assertDontSee('Henüz bir yazı yayınlanmadı');
    });

    it('keeps the testimonials section and says so when there are none', function () {
        $this->get(route('home'))
            ->assertSeeInOrder(['Müşteri Yorumları', 'Henüz bir müşteri yorumu eklenmedi'])
            ->assertDontSee('data-marquee', escape: false);
    });

    it('shows the marquee and no empty state once there are testimonials', function () {
        Testimonial::factory()->create();

        $this->get(route('home'))->assertSee('data-marquee', escape: false)->assertDontSee('Henüz bir müşteri yorumu eklenmedi');
    });

    it('does not offer "see all" links that lead to empty lists', function () {
        $html = $this->get(route('home'))->getContent();
        $main = Str::between($html, '<main', '</main>');

        expect($main)->not->toContain('Tümünü gör');
    });
});

describe('listing pages', function () {
    it('says that nothing is published yet', function (string $route, string $message) {
        $this->get(route($route))->assertOk()->assertSee($message)->assertSee('data-empty-state', escape: false);
    })->with([
        'blog' => ['blog', 'Henüz bir yazı yayınlanmadı'],
        'projects' => ['projects', 'Henüz bir proje yayınlanmadı'],
        'references' => ['references', 'Henüz bir müşteri yorumu eklenmedi'],
        'bookmarks' => ['bookmarks', 'Henüz bir yer işareti eklenmedi'],
        'stack' => ['stack', 'Henüz bir teknoloji eklenmedi'],
        'services' => ['services', 'Henüz bir hizmet eklenmedi'],
    ]);

    it('drops the empty state once there is content', function (string $route, Closure $seed, string $message) {
        $seed();

        $this->get(route($route))->assertOk()->assertDontSee($message);
    })->with([
        'blog' => ['blog', fn () => Post::factory()->published()->for(Category::factory())->create(), 'Henüz bir yazı yayınlanmadı'],
        'projects' => ['projects', fn () => Project::factory()->create(), 'Henüz bir proje yayınlanmadı'],
        'references' => ['references', fn () => Testimonial::factory()->create(), 'Henüz bir müşteri yorumu eklenmedi'],
        'bookmarks' => ['bookmarks', fn () => Bookmark::factory()->for(BookmarkCategory::factory(), 'category')->create(), 'Henüz bir yer işareti eklenmedi'],
        'stack' => ['stack', fn () => Skill::factory()->create(), 'Henüz bir teknoloji eklenmedi'],
        'services' => ['services', fn () => Service::factory()->create(), 'Henüz bir hizmet eklenmedi'],
    ]);

    it('tells an empty filter apart from an empty blog', function () {
        Post::factory()->published()->for(Category::factory())->create();
        $emptyCategory = Category::factory()->create(['name' => 'Boş Kategori']);

        $this->get(route('blog.category', $emptyCategory))
            ->assertSee('Bu filtrede yazı bulunamadı')
            ->assertDontSee('Henüz bir yazı yayınlanmadı');
    });

    it('hides the project filters when there is nothing to filter', function () {
        $this->get(route('projects'))->assertDontSee('data-filter-group="proj"', escape: false);

        Project::factory()->create();

        $this->get(route('projects'))->assertSee('data-filter-group="proj"', escape: false);
    });

    it('still shows the brands on the references page when only testimonials are missing', function () {
        Brand::factory()->create(['name' => 'Acme']);

        $this->get(route('references'))->assertSee('Birlikte çalıştığım markalar')->assertSee('Henüz bir müşteri yorumu eklenmedi');
    });

    it('ignores bookmark categories that have no bookmarks', function () {
        BookmarkCategory::factory()->create(['name' => 'Boş Kategori']);

        $this->get(route('bookmarks'))->assertSee('Henüz bir yer işareti eklenmedi')->assertDontSee('Boş Kategori');
    });
});

it('leaves the cv alone: empty sections are simply omitted there', function () {
    $this->get(route('cv'))->assertOk()->assertDontSee('data-empty-state', escape: false);
});

it('does not print the empty list container, which would double the spacing below the empty state', function (string $route, string $container) {
    $this->get(route($route))->assertDontSee($container, escape: false);
})->with([
    'services' => ['services', 'flex flex-wrap gap-5 justify-center mt-14 mb-20'],
    'stack' => ['stack', 'mt-16 flex flex-col gap-16'],
    'bookmarks' => ['bookmarks', 'mt-16 flex flex-col gap-16'],
]);

it('does not leave a dangling separator on the home profile card when the location is empty', function () {
    $settings = app(GeneralSettings::class);
    $settings->author_title = 'Full-stack Developer';
    $settings->author_location = null;
    $settings->save();

    expect($this->get(route('home'))->getContent())->not->toMatch('/Full-stack Developer\s*·\s*</u');

    $settings->author_location = 'Kütahya, TR';
    $settings->save();

    $this->get(route('home'))->assertSee('Full-stack Developer · Kütahya, TR');
});

it('calls the stack page "Teknolojiler" on the home profile card too', function () {
    $main = Str::between($this->get(route('home'))->getContent(), '<main', '</main>');

    expect(preg_match('/(?<![\w-])Stack(?![\w-])/u', $main))->toBe(0);
});

it('hides the footer contact column when there is no email and no social profile', function () {
    $footer = Str::after($this->get(route('home'))->getContent(), 'role="contentinfo"');

    expect($footer)->not->toContain('Bağlan');

    $settings = app(GeneralSettings::class);
    $settings->author_email = 'ada@example.test';
    $settings->save();

    expect(Str::after($this->get(route('home'))->getContent(), 'role="contentinfo"'))->toContain('Bağlan');
});
