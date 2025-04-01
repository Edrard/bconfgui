<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Group;

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
use Illuminate\Contracts\Database\Eloquent\Builder;
use Throwable;


class GroupIndexPage extends IndexPage
{
    protected string $title = 'groups';
    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [
            Text::make(__('elem.name'), 'group')->sortable(),
            Text::make(__('elem.desc'), 'description')->sortable(),
            Date::make(__('elem.created_at'), 'created_at')->withTime()->sortable(),
            Date::make(__('elem.updated_at'), 'updated_at')->withTime()->sortable()
        ];
    }
    public function getTitle(): string
    {
        return __('group.'.$this->title);
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
