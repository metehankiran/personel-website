<?php

declare(strict_types=1);

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceAreaController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

/*
 * Public urls are Turkish because visitors see them; route names stay English
 * like the rest of the code, so views and controllers never change with a url.
 */
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/hakkimda', [PageController::class, 'about'])->name('about');
Route::get('/hizmetler', [PageController::class, 'services'])->name('services');
Route::get('/cv', [PageController::class, 'cv'])->name('cv');
Route::get('/iletisim', [PageController::class, 'contact'])->name('contact');
Route::get('/sss', [PageController::class, 'faq'])->name('faq');

Route::get('/referanslar', ReferenceController::class)->name('references');
Route::get('/teknolojiler', SkillController::class)->name('stack');
Route::get('/yer-isaretlerim', BookmarkController::class)->name('bookmarks');

Route::get('/projeler', [ProjectController::class, 'index'])->name('projects');
Route::get('/projeler/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/hizmet-bolgeleri', [ServiceAreaController::class, 'index'])->name('service-areas');
Route::get('/hizmet-bolgeleri/{serviceArea:slug}', [ServiceAreaController::class, 'show'])->name('service-areas.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/kategori/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/etiket/{tag:slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/bulten/abone-ol', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/bulten/abonelikten-cik/{email}/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/ara', [SearchController::class, 'results'])->name('search');
Route::get('/search/index', [SearchController::class, 'index'])->name('search.index');

Route::get('/sayfa/{page:slug}', [PageController::class, 'show'])->name('pages.show');

// Read by crawlers, not people, so these keep their conventional English names.
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('llms');
Route::get('/feed.xml', [SeoController::class, 'feed'])->name('feed');
Route::get('/favicon.ico', [SeoController::class, 'favicon'])->name('favicon');
