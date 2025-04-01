<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\GroupResource;
use App\MoonShine\Resources\ResModelResource;
use App\MoonShine\Resources\TypeResource;
use App\MoonShine\Resources\ConnectResource;
use App\MoonShine\Resources\ConfigResource;
use App\MoonShine\Resources\DeviceConfigResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        // $config->authEnable();

        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                GroupResource::class,
                ResModelResource::class,
                TypeResource::class,
                ConnectResource::class,
                ConfigResource::class,
                DeviceConfigResource::class
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }
}
