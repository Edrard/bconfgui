<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\Forms\LoginForm;
use MoonShine\Laravel\Layouts\LoginLayout;

class LoginPage extends Page
{
    protected ?string $layout = LoginLayout::class;

    protected function components(): iterable
    {
        yield moonshineConfig()->getForm('login', LoginForm::class);
    }
}
