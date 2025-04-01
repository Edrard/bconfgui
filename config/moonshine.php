<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MoonShine\Laravel\Exceptions\MoonShineNotFoundException;
use MoonShine\Laravel\Forms\FiltersForm;
use MoonShine\Laravel\Forms\LoginForm;
use MoonShine\Laravel\Http\Middleware\Authenticate;
use MoonShine\Laravel\Http\Middleware\ChangeLocale;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\Pages\Dashboard;
use MoonShine\Laravel\Pages\ErrorPage;
use MoonShine\Laravel\Pages\LoginPage;
use MoonShine\Laravel\Pages\ProfilePage;

use App\MoonShine\Pages\DeviceConfig\DeviceConfigIndexPage;

return [
    'title' => env('MOONSHINE_TITLE', 'Bconf'),
    'logo' => 'vendor/images/big_bconf_string_white.png',
    'logo_small' => 'vendor/images/bconf_logo_white.png',


    // Default flags
    'use_migrations' => true,
    'use_notifications' => true,
    'use_database_notifications' => true,

    // Routing
    'domain' => env('MOONSHINE_DOMAIN'),
    'prefix' => env('MOONSHINE_ROUTE_PREFIX', ''),
    'page_prefix' => env('MOONSHINE_PAGE_PREFIX', 'page'),
    'resource_prefix' => env('MOONSHINE_RESOURCE_PREFIX', ''),
    //'home_route' => 'moonshine.index',
    'home_url' => '/device_configs/device-config-index-page',

    // Error handling
    'not_found_exception' => MoonShineNotFoundException::class,

    // Middleware
    'middleware' => [
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        ChangeLocale::class,
    ],

    // Storage
    'disk' => 'public',
    'disk_options' => [],
    'cache' => 'file',

    // Authentication and profile
    'auth' => [
        'enabled' => true,
        'guard' => 'moonshine',
        'model' => MoonshineUser::class,
        'middleware' => Authenticate::class,
        'pipelines' => [],
    ],

    // Authentication and profile
    'user_fields' => [
        'username' => 'email',
        'password' => 'password',
        'name' => 'name',
        'avatar' => 'avatar',
    ],

    // Layout, pages, forms
    'layout' => App\MoonShine\Layouts\MoonShineLayout::class,

    'forms' => [
        'login' => App\MoonShine\Forms\LoginForm::class,
        'filters' => App\MoonShine\Forms\FiltersForm::class,
    ],

    'pages' => [
        'dashboard' => App\MoonShine\Pages\Dashboard::class,
        'profile' => App\MoonShine\Pages\ProfilePage::class,
        'login' => App\MoonShine\Pages\LoginPage::class,
        'error' => App\MoonShine\Pages\ErrorPage::class,
    ],

    // Localizations
    'locale' => env('APP_LOCALE', 'en'),
    'locales' => explode(',',env('APP_LOCALE_SWITCH', 'en')),
];
