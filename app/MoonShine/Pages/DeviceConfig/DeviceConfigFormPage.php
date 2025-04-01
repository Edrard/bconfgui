<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\DeviceConfig;


use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Collections\Fields;
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
use MoonShine\UI\Components\Layout\Div;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasOne;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use App\MoonShine\Resources\ConnectResource;
use App\MoonShine\Resources\GroupResource;
use App\MoonShine\Resources\TypeResource;
use App\MoonShine\Resources\ResModelResource;
use Throwable;


class DeviceConfigFormPage extends FormPage
{
    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [
            Grid::make([
                Column::make(
                    [
                        Hidden::make('id'),
                        Text::make('device.name', 'name')
                        ->translatable()
                        ->badge(function($status, Field $field){
                            return $field->getData()->toArray()['status'] != 1 ? 'red' : 'green';
                        }),
                        Text::make('device.description', 'description')->translatable(),
                        BelongsTo::make('device.group', 'group', resource: GroupResource::class)
                        ->translatable(),
                        BelongsTo::make('device.connect', 'connect', resource: ConnectResource::class)
                        ->translatable(),
                        BelongsTo::make('device.type', 'type', resource: TypeResource::class)
                        ->translatable(),
                        BelongsTo::make('device.model', 'model', resource: ResModelResource::class)
                        ->translatable(),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        Text::make('device.ip', 'ip')->translatable(),
                        Text::make('device.port', 'port')->translatable(),
                        Text::make('device.login', 'login')->translatable()
                        ->hint(__('device.login_hint')),
                        Text::make('device.password', 'password')
                        ->translatable()
                        ->class(['password'])
                        ->customAttributes(['type' => 'password'], true),
                        //->style(['-webkit-text-security: disc;']),
                        Text::make('device.config_search', 'config_search')->translatable(),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        Switcher::make('device.config_enable', 'config_enable')
                        ->translatable()
                        ->default(1)
                        ->setValue($this->getResource()->getItem()?->config_enable),

                        Text::make('device.config_enable_command', 'config_enable_command')
                        ->translatable()
                        ->showWhen('config_enable', 1),
                        Text::make('device.config_enable_pass', 'config_enable_pass')
                        ->translatable()
                        ->class(['password'])
                        ->customAttributes(['type' => 'password'], true)
                        ->showWhen('config_enable', 1),
                        Text::make('device.config_enable_pass_str', 'config_enable_pass_str')
                        ->translatable()
                        ->showWhen('config_enable', 1)
                        ->hint(__('device.config_enable_pass_str_hint')),


                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
            ]),
        ];
    }
    protected function getFormComponent(
        string $action,
        ?DataWrapperContract $item,
        Fields $fields,
        bool $isAsync = true,
    ): ComponentContract
    {

        return (parent::getFormComponent($action,$item,$fields,$isAsync)->submit(__('elem.save'))->asyncMethod('updateDevice'));

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
