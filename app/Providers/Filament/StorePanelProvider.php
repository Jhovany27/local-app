<?php

namespace App\Providers\Filament;


use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\EnsureStoreSelected;
use Filament\Navigation\NavigationItem;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Navigation\MenuItem;



class StorePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('store')
            ->path('store')
            ->login(\App\Filament\Store\Pages\Auth\Login::class)
            ->brandName('Panel Tienda')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): string => Blade::render("@vite('resources/css/app.css')")
            )
            ->colors([
                'primary' => Color::Lime,
            ])
            ->discoverResources(
                in: app_path('Filament/Store/Resources'),
                for: 'App\\Filament\\Store\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Store/Pages'),
                for: 'App\\Filament\\Store\\Pages'
            )
            ->pages([
                \App\Filament\Store\Pages\SeleccionarTienda::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Store/Widgets'),
                for: 'App\\Filament\\Store\\Widgets'
            )
            ->widgets([
                AccountWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Cambiar tienda')
                    ->icon('heroicon-o-arrows-right-left')
                    ->url(fn() => route('store.cambiar-tienda')),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Ir al portal')
                    ->icon('heroicon-o-home')
                    ->url(fn() => route('portal')),
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsureStoreSelected::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
