<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Community\EventSubmissionController;
use App\Http\Controllers\Community\ForumController as CommunityForumController;
use App\Http\Controllers\Community\NewsSubmissionController;
use App\Http\Controllers\Community\OrganizationSubmissionController;
use App\Http\Controllers\CommunityDashboardController;
use App\Http\Controllers\CommunityOrganizationController;
use App\Http\Controllers\ContributorsController;
use App\Http\Controllers\DestinationReviewController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ExploreReviewController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\InstitutionReviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PlanTripController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServicePlaceController;
use App\Http\Controllers\ServicePlaceReviewController;
use App\Http\Controllers\SmartTripPlannerController;
use App\Http\Controllers\StayEatController;
use App\Http\Controllers\StayEatReviewController;
use App\Http\Controllers\TravelGuideController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Home / Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Language Switcher Route
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['si', 'en', 'ta'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return redirect()->back();
})->name('lang.switch');

// Intelligent /dashboard redirect based on role
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->hasAnyRole(['admin', 'super_admin'])) {
        return redirect('/admin');
    }

    return redirect()->route('community.dashboard');
})->middleware(['auth'])->name('dashboard');

// Auth User Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Report Inaccurate Information Route
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // Comments Routes
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Community Member Dashboard & Submissions (Protected by Auth for all logged in users)
Route::middleware(['auth'])
    ->prefix('community')
    ->name('community.')
    ->group(function () {

        Route::get('/dashboard', [CommunityDashboardController::class, 'index'])
            ->name('dashboard');

        // Blog Posts Submissions
        Route::get('/blog-posts/create', [BlogPostController::class, 'create'])->name('blog-posts.create');
        Route::post('/blog-posts', [BlogPostController::class, 'store'])->name('blog-posts.store');
        Route::get('/blog-posts/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('blog-posts.edit');
        Route::put('/blog-posts/{blogPost}', [BlogPostController::class, 'update'])->name('blog-posts.update');
        Route::delete('/blog-posts/{blogPost}', [BlogPostController::class, 'destroy'])->name('blog-posts.destroy');
        Route::post('/blog-posts/{blogPost}/submit', [BlogPostController::class, 'submitForReview'])->name('blog-posts.submit');

        // News Submissions
        Route::get('/news/create', [NewsSubmissionController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsSubmissionController::class, 'store'])->name('news.store');
        Route::get('/news/{newsPost}/edit', [NewsSubmissionController::class, 'edit'])->name('news.edit');
        Route::put('/news/{newsPost}', [NewsSubmissionController::class, 'update'])->name('news.update');
        Route::delete('/news/{newsPost}', [NewsSubmissionController::class, 'destroy'])->name('news.destroy');
        Route::post('/news/{newsPost}/submit', [NewsSubmissionController::class, 'submitForReview'])->name('news.submit');

        // Events Submissions
        Route::get('/events/create', [EventSubmissionController::class, 'create'])->name('events.create');
        Route::post('/events', [EventSubmissionController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventSubmissionController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventSubmissionController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventSubmissionController::class, 'destroy'])->name('events.destroy');
        Route::post('/events/{event}/submit', [EventSubmissionController::class, 'submitForReview'])->name('events.submit');

        // Organizations Submissions
        Route::get('/organizations/create', [OrganizationSubmissionController::class, 'create'])->name('organizations.create');
        Route::post('/organizations', [OrganizationSubmissionController::class, 'store'])->name('organizations.store');
        Route::get('/organizations/{organization}/edit', [OrganizationSubmissionController::class, 'edit'])->name('organizations.edit');
        Route::put('/organizations/{organization}', [OrganizationSubmissionController::class, 'update'])->name('organizations.update');
        Route::delete('/organizations/{organization}', [OrganizationSubmissionController::class, 'destroy'])->name('organizations.destroy');
        Route::post('/organizations/{organization}/submit', [OrganizationSubmissionController::class, 'submitForReview'])->name('organizations.submit');

        // Community Member Forum Actions
        Route::get('/forum/create', [CommunityForumController::class, 'create'])->name('forum.create');
        Route::post('/forum', [CommunityForumController::class, 'store'])->name('forum.store');
        Route::delete('/forum/{topic}', [CommunityForumController::class, 'destroy'])->name('forum.destroy');
        Route::post('/forum/{topic}/reply', [CommunityForumController::class, 'storeReply'])->name('forum.reply.store');
        Route::delete('/forum/reply/{reply}', [CommunityForumController::class, 'destroyReply'])->name('forum.reply.destroy');
    });

// Public Portal Routes under /community/
Route::prefix('community')->name('community.')->group(function () {

    // News Public
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Events Public
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

    // Forum Public
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/{slug}', [ForumController::class, 'show'])->name('forum.show');

    // Blog Public
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

    // Organizations Public
    Route::get('/organizations', [CommunityOrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/organizations/{slug}', [CommunityOrganizationController::class, 'show'])->name('organizations.show');
});

// Legacy / Direct Shortcuts
Route::redirect('/blog', '/community/blog');
Route::redirect('/news', '/community/news');
Route::redirect('/events', '/community/events');
Route::redirect('/forum', '/community/forum');

// Placeholder routes for Explore, Plan, Services, Stay & Eat, Emergency, Downloads

Route::prefix('explore')
    ->name('explore.')
    ->group(function () {

        Route::get('/', [ExploreController::class, 'index'])
            ->name('index');

        Route::get('/destinations', [ExploreController::class, 'destinations'])
            ->name('destinations.index');

        Route::get('/destinations/{slug}', [ExploreController::class, 'destination'])
            ->name('destinations.show');

        Route::get('/map', [ExploreController::class, 'map'])
            ->name('map');

        Route::get('/culture-heritage', [ExploreController::class, 'cultureHeritage'])
            ->name('culture-heritage.index');

        Route::get('/culture-heritage/{slug}', [ExploreController::class, 'cultureHeritageShow'])
            ->name('culture-heritage.show');

        Route::get('/outdoor-adventure', [ExploreController::class, 'outdoorAdventure'])
            ->name('outdoor-adventure.index');

        Route::get('/outdoor-adventure/{slug}', [ExploreController::class, 'outdoorAdventureShow'])
            ->name('outdoor-adventure.show');

        Route::middleware('auth')->group(function () {

            Route::post(
                '/destinations/{destination}/reviews',
                [DestinationReviewController::class, 'store']
            )->name('destinations.reviews.store');

            Route::post(
                '/items/{item}/reviews',
                [ExploreReviewController::class, 'store']
            )->name('reviews.store');

            Route::delete(
                '/reviews/{review}',
                [ExploreReviewController::class, 'destroy']
            )->name('reviews.destroy');

        });

    });

// =============================================
// STAY & EAT ROUTES
// =============================================
Route::prefix('stay-eat')
    ->name('stay-eat.')
    ->group(function () {
        Route::get('/', [StayEatController::class, 'index'])
            ->name('index');

        Route::get('/{section}', [StayEatController::class, 'section'])
            ->name('section');

        Route::get('/{section}/{slug}', [StayEatController::class, 'show'])
            ->name('show');

        Route::middleware('auth')->group(function () {
            Route::post(
                '/items/{item}/reviews',
                [StayEatReviewController::class, 'store']
            )->name('reviews.store');

            Route::delete(
                '/reviews/{review}',
                [StayEatReviewController::class, 'destroy']
            )->name('reviews.destroy');
        });
    });

// =============================================
// SERVICES ROUTES (Government Institutions, Health, Shops, Banks, Fuel, Education)
// =============================================
Route::prefix('services')
    ->name('services.')
    ->group(function () {
        // Government Institutions listing & profiles
        Route::get('/government-institutions', [InstitutionController::class, 'index'])
            ->name('institutions.index');
        Route::get('/institutions', [InstitutionController::class, 'index'])
            ->name('institutions.index.short');

        Route::get('/government-institutions/{slug}', [InstitutionController::class, 'show'])
            ->name('institutions.show');
        Route::get('/institutions/{slug}', [InstitutionController::class, 'show'])
            ->name('institutions.show.short');

        // Review submissions & Admin review deletion for Institutions
        Route::middleware('auth')->group(function () {
            Route::post(
                '/institutions/{institution}/reviews',
                [InstitutionReviewController::class, 'store']
            )->name('institutions.reviews.store');

            Route::delete(
                '/institutions/reviews/{review}',
                [InstitutionReviewController::class, 'destroy']
            )->name('institutions.reviews.destroy');
        });

        // Other Service Categories: health, shops-businesses, banks-atms, fuel-ev, education
        Route::get('/{section}', [ServicePlaceController::class, 'section'])
            ->whereIn('section', ['health', 'shops-businesses', 'banks-atms', 'fuel-ev', 'education'])
            ->name('places.section');

        Route::get('/{section}/{slug}', [ServicePlaceController::class, 'show'])
            ->whereIn('section', ['health', 'shops-businesses', 'banks-atms', 'fuel-ev', 'education'])
            ->name('places.show');

        // Review submissions & Admin review deletion for Service Places
        Route::middleware('auth')->group(function () {
            Route::post(
                '/places/{servicePlace}/reviews',
                [ServicePlaceReviewController::class, 'store']
            )->name('places.reviews.store');

            Route::delete(
                '/places/reviews/{review}',
                [ServicePlaceReviewController::class, 'destroy']
            )->name('places.reviews.destroy');
        });
    });

// =============================================
// PLAN YOUR TRIP ROUTES
// =============================================
Route::prefix('plan')
    ->name('plan.')
    ->group(function () {

        Route::get(
            '/smart-trip-planner',
            [SmartTripPlannerController::class, 'create']
        )->name('smart-trip-planner.index');

        Route::get(
            '/smart-planner',
            [SmartTripPlannerController::class, 'create']
        )->name('smart-planner.index');

        Route::post(
            '/smart-trip-planner/generate',
            [SmartTripPlannerController::class, 'generate']
        )->name('smart-trip-planner.generate');

        Route::post(
            '/smart-planner/generate',
            [SmartTripPlannerController::class, 'generate']
        )->name('smart-planner.generate');

        // 1. Travel Guide — Public (read-only, admin creates via Filament)
        Route::get('/travel-guide', [TravelGuideController::class, 'index'])
            ->name('travel-guide.index');
        Route::get('/travel-guide/{slug}', [TravelGuideController::class, 'show'])
            ->name('travel-guide.show');

        // 2. Public Transport — Public listing
        Route::get('/public-transport', [PlanTripController::class, 'publicTransport'])
            ->name('transport.index');

        // 3. Weather & Safety — Public page
        Route::get('/weather-safety', [PlanTripController::class, 'weatherSafety'])
            ->name('weather.index');

        // 4. Mobile Coverage — Public page
        Route::get('/mobile-coverage', [PlanTripController::class, 'mobileCoverage'])
            ->name('coverage.index');

        // Auth-protected submission routes (community users + admin)
        Route::middleware('auth')->group(function () {

            // Submit transport info
            Route::post('/public-transport', [PlanTripController::class, 'storeTransport'])
                ->name('transport.store');
            Route::put('/public-transport/{publicTransport}', [PlanTripController::class, 'updateTransport'])
                ->name('transport.update');
            Route::patch('/public-transport/{publicTransport}/status', [PlanTripController::class, 'updateTransportStatus'])
                ->name('transport.update-status');

            // Submit weather observation
            Route::post('/weather-safety', [PlanTripController::class, 'storeWeatherObservation'])
                ->name('weather.store');
            Route::post('/weather-safety/safety-alert', [PlanTripController::class, 'storeSafetyAlert'])
                ->name('weather.safety-alert.store');
            Route::post('/weather-safety/location', [PlanTripController::class, 'storeWeatherLocation'])
                ->name('weather.location.store');
            Route::delete('/weather-safety/observation/{weatherObservation}', [PlanTripController::class, 'destroyWeatherObservation'])
                ->name('weather.observation.destroy');
            Route::delete('/weather-safety/safety-alert/{safetyAlert}', [PlanTripController::class, 'destroySafetyAlert'])
                ->name('weather.safety-alert.destroy');

            // Submit mobile coverage report
            Route::post('/mobile-coverage', [PlanTripController::class, 'storeMobileCoverage'])
                ->name('coverage.store');
            Route::put('/mobile-coverage/{mobileCoverageReport}', [PlanTripController::class, 'updateMobileCoverage'])
                ->name('coverage.update');
            Route::delete('/mobile-coverage/{mobileCoverageReport}', [PlanTripController::class, 'destroyMobileCoverage'])
                ->name('coverage.destroy');
        });
    });

// =============================================
// EMERGENCY SERVICES ROUTES
// =============================================
Route::prefix('emergency')
    ->name('emergency.')
    ->group(function () {
        Route::get('/', [EmergencyController::class, 'index'])->name('index');
        Route::get('/contacts', [EmergencyController::class, 'contacts'])->name('contacts');
        Route::get('/hospitals', [EmergencyController::class, 'hospitals'])->name('hospitals');
        Route::get('/police', [EmergencyController::class, 'police'])->name('police');
        Route::get('/wildlife-forest', [EmergencyController::class, 'wildlifeForest'])->name('wildlife-forest');
        Route::get('/vehicle-assistance', [EmergencyController::class, 'vehicleAssistance'])->name('vehicle-assistance');
    });

Route::middleware('auth')->group(function () {

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::post(
        '/notifications/{notification}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    )->name('notifications.read-all');

});

// Legacy shortcut
Route::get('/services/emergency', [EmergencyController::class, 'contacts'])->name('services.emergency');

// =============================================
// STATIC PAGES (About, Guidelines, Contributors)
// =============================================
Route::get('/about', [PagesController::class, 'about'])->name('pages.about');
Route::get('/contributor-guidelines', [PagesController::class, 'contributorGuidelines'])->name('pages.contributor-guidelines');
Route::get('/contributors', [ContributorsController::class, 'index'])->name('pages.contributors');

// Remote Storage Redirect Fallback (when media is hosted on external cloud storage)
Route::get('/storage/{path}', function (string $path) {
    $mediaUrl = config('filesystems.media_url');
    if (! empty($mediaUrl)) {
        return redirect()->away(rtrim($mediaUrl, '/').'/'.ltrim($path, '/'), 301);
    }
    abort(404);
})->where('path', '.*');

Route::fallback(function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
