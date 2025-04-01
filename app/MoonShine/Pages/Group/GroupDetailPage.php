<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Group;

use MoonShine\Laravel\Pages\Crud\DetailPage;
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
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use Throwable;


class GroupDetailPage extends DetailPage
{

    protected string $title = 'g_detail';

    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [

            Text::make(__('elem.name'), 'group'),
            Text::make(__('elem.desc'), 'description'),
            Date::make(__('elem.created_at'), 'created_at')->withTime(),
            Date::make(__('elem.updated_at'), 'updated_at')->withTime(),
        ];
    }
    public function getTitle(): string
    {
        return __('group.'.$this->title).': <b>'.$this->getResource()->getItem()->group.'</b>';
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
