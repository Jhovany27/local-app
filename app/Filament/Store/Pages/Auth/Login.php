<?php

namespace App\Filament\Store\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.store.pages.auth.login';


    public function getLayout(): string
    {
        return 'layouts.blank'; //  usamos un layout vacío
    }

    protected function getRedirectUrl(): string
    {
        return filament()->getUrl();
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getTitle(): string|Htmlable
    {
        return '';
    }
}
