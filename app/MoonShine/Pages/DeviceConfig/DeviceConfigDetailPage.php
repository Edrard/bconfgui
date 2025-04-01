<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\DeviceConfig;

use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
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
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Resources\ConnectResource;
use App\MoonShine\Resources\GroupResource;
use App\MoonShine\Resources\TypeResource;
use App\MoonShine\Resources\ResModelResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Collections\Fields;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Support\Enums\Color;
use MoonShine\UI\Fields\Field;
use App\Models\DevicesConfig;
use Throwable;


class DeviceConfigDetailPage extends DetailPage
{
    protected string $title = 'd_detail';
    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [
            Text::make('device.name', 'name')
            ->translatable()
            ->badge(function($status, Field $field){
                return $field->getData()->toArray()['status'] != 1 ? 'red' : 'green';
            }),
            Text::make('device.description', 'description')->translatable(),
            BelongsTo::make('device.group', 'group', resource: GroupResource::class)
            ->translatable(),
            Text::make('device.ip', 'ip')->translatable(),
            Text::make('device.port', 'port')->translatable(),
            Text::make('device.login', 'login')->translatable(),
            Password::make('device.password', 'password')->translatable(),
            BelongsTo::make('device.connect', 'connect', resource: ConnectResource::class)
            ->translatable(),
            BelongsTo::make('device.type', 'type', resource: TypeResource::class)
            ->translatable(),
            BelongsTo::make('device.model', 'model', resource: ResModelResource::class)
            ->translatable(),
            Text::make('device.config_search', 'config_search')->translatable(),
            Checkbox::make('device.config_enable', 'config_enable')
            ->translatable(),
            Text::make('device.config_enable_command', 'config_enable_command')
            ->translatable()
            ->canSee(function (Field $ch){
                return $this->getResource()->getItem()->config_enable == 1 ? TRUE : FALSE;
            })
            ->customWrapperAttributes(['style' => 'padding-left: 20px;']),
            Password::make('device.config_enable_pass', 'config_enable_pass')
            ->translatable()
            ->canSee(function (Field $ch){
                return $this->getResource()->getItem()->config_enable == 1 ? TRUE : FALSE;
            })
            ->customWrapperAttributes(['style' => 'padding-left: 20px;']),
            Text::make('device.config_enable_pass_str', 'config_enable_pass_str')
            ->translatable()
            ->canSee(function (Field $ch){
                return $this->getResource()->getItem()->config_enable == 1 ? TRUE : FALSE;
            })
            ->customWrapperAttributes(['style' => 'padding-left: 20px;']),

            Text::make('device.created_at', 'created_at')->translatable(),
            Text::make('device.updated_at', 'updated_at')->translatable(),
        ];
    }
    protected function getDetailComponent(?DataWrapperContract $item, Fields $fields): ComponentContract
    {
        return TableBuilder::make($fields)
        ->cast($this->getResource()->getCaster())
        ->items([$item])
        ->vertical()
        ->simple()
        ->preview();
    }
    public function getTitle(): string
    {

        return __('device.'.$this->title).': <b>'.$this->getResource()->getItem()->name.'</b>';
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
