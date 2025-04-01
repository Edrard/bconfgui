<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use \App\MoonShine\Resources\{MoonShineUserResource, MoonShineUserRoleResource};

use Carbon\Carbon;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Components\Fragment;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\MenuManager\{MenuItem, MenuGroup, MenuDivider};
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\{Breadcrumbs,
Components,
Layout\Flash,
Layout\Div,
Layout\Body,
Layout\Burger,
Layout\Content,
Layout\Footer,
Layout\Head,
Layout\Favicon,
Layout\Assets,
Layout\Meta,
Layout\Header,
Layout\Html,
Layout\Layout,
Layout\Logo,
Layout\Menu,
Layout\Sidebar,
Layout\ThemeSwitcher,
Layout\TopBar,
Layout\Wrapper,
When,
Title};
use App\MoonShine\Resources\{ResModelResource,ConnectResource,DeviceConfigResource,TypeResource,GroupResource,ConfigResource};




final class MoonShineLayout extends AppLayout
{
    protected $favicon_path = '/vendor/favicon/';

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [

            MenuItem::make('Config', ConfigResource::class,'cog-6-tooth')->translatable('menu'),
            MenuDivider::make(),
            MenuItem::make('DeviceConfig', DeviceConfigResource::class,'server-stack')->translatable('menu'),
            MenuDivider::make(),
            MenuItem::make('Groups', GroupResource::class,'rectangle-group')->translatable('menu'),
            MenuDivider::make(),
            MenuItem::make('Type', TypeResource::class,'rectangle-stack')->translatable('menu'),
            MenuDivider::make(),
            MenuItem::make('Connect', ConnectResource::class,'link')->translatable('menu'),
            MenuDivider::make(),
            MenuItem::make('Model', ResModelResource::class,'view-columns')->translatable('menu'),

            MenuDivider::make(__('moonshine::ui.resource.system')),
            MenuGroup::make(static fn () => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn () => __('moonshine::ui.resource.admins_title'),
                    MoonShineUserResource::class
                ),
                MenuItem::make(
                    static fn () => __('moonshine::ui.resource.role_title'),
                    MoonShineUserRoleResource::class
                ),
            ]),
        ];
    }

    /**
    * @param ColorManager $colorManager
    */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);
    }
    protected function getFaviconComponent(): Favicon
    {
        return parent::getFaviconComponent()->customAssets([
            'apple-touch' => $this->favicon_path.'apple-touch-icon.png',
            '32' => $this->favicon_path.'favicon-32x32.png',
            '16' => $this->favicon_path.'favicon-16x16.png',
            'safari-pinned-tab' => $this->favicon_path.'favicon.svg',
            'web-manifest' => $this->favicon_path.'site.webmanifest',
        ]);
    }
    protected function getFooterComponent(): Footer
    {
        return parent::getFooterComponent()->copyright('Bconf 2024 - '.Carbon::now()->year)?->menu(['https://github.com/Edrard/bconf' => 'Documentation']);
    }
    protected function getLogoComponent(): Logo
    {

        return parent::getLogoComponent(); #->view('logo')->render();
    }
    public function build(): Layout
    {
        return Layout::make([
            Html::make([
                $this->getHeadComponent(),
                Body::make([
                    Wrapper::make([
                        // $this->getTopBarComponent(),
                        $this->getSidebarComponent(),

                        Div::make([
                            Fragment::make([
                                Flash::make(),

                                $this->getHeaderComponent(),

                                Content::make([
                                    Title::make($this->getPage()->getTitle())->class('mb-6'),
                                    Components::make(
                                        $this->getPage()->getComponents()
                                    ),
                                ]),

                                $this->getFooterComponent(),
                            ])->class('layout-page')->name(self::CONTENT_FRAGMENT_NAME),
                        ])->class('flex grow overflow-auto')->customAttributes(['id' => self::CONTENT_ID]),
                    ]),
                ]),
            ])
            ->customAttributes([
                'lang' => $this->getHeadLang(),
            ])
            ->withAlpineJs()
            ->withThemes(),
        ]);
    }
}
