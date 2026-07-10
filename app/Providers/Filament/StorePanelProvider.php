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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;



class StorePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('store')
            ->path('store')
            ->login(\App\Filament\Store\Pages\Auth\Login::class)
            ->darkMode(false)
            ->brandName(function (): HtmlString {
                $tiendaId = session('store_tienda_id');
                $tienda = $tiendaId ? \App\Models\Tienda::find($tiendaId) : null;

                if (!$tienda) {
                    return new HtmlString('Panel Tienda');
                }

                $nombre = e($tienda->tie_nombre);
                $dir    = e(Str::limit($tienda->tie_direccion, 32));

                return new HtmlString(
                    "<span style='display:flex;flex-direction:column;gap:0;line-height:1.25'>
                        <span>{$nombre}</span>
                        <span style='font-size:0.68rem;font-weight:400;opacity:0.55'>{$dir}</span>
                    </span>"
                );
            })
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): string => Blade::render("@vite('resources/css/app.css')")
            )
            ->colors([
                'primary' => Color::Lime,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('15s')
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
                \App\Filament\Store\Pages\CorteCaja::class,
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
                    ->url(fn() => route('store.cambiar-tienda'))
                    ->sort(0),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Ir al portal')
                    ->icon('heroicon-o-home')
                    ->url(fn() => route('portal')),
                MenuItem::make()
                    ->label('Registrar nueva tienda')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn() => route('registro.tienda')),
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
