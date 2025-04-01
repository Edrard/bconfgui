<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\DeviceConfig;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasOne;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\MoonShine\Resources\ConnectResource;
use App\MoonShine\Resources\GroupResource;
use App\MoonShine\Resources\TypeResource;
use App\MoonShine\Resources\ResModelResource;
use Throwable;


class DeviceConfigIndexPage extends IndexPage
{
    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [
            Text::make(__('device.name'), 'name')->sortable(),
            Text::make(__('device.ip'), 'ip')->sortable(),

            BelongsTo::make('device.group', 'group', resource: GroupResource::class)
            ->sortable()
            ->translatable(),
            BelongsTo::make('device.type', 'type', resource: TypeResource::class)
            ->sortable()
            ->translatable(),
            BelongsTo::make('device.model', 'model', resource: ResModelResource::class)
            ->sortable()
            ->translatable(),
        ];
    }

    /**
    * @return list<ComponentContract>
    * @throws Throwable
    */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
    * @return list<ComponentContract>
    * @throws Throwable
    */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
    * @return list<ComponentContract>
    * @throws Throwable
    */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
