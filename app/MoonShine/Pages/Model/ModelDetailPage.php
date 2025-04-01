<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Model;

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


class ModelDetailPage extends DetailPage
{
    protected string $title = 'm_detail';

    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [

            Text::make(__('elem.name'), 'model'),
            Text::make(__('elem.desc'), 'description'),
            Date::make(__('elem.created_at'), 'created_at')->withTime(),
            Date::make(__('elem.updated_at'), 'updated_at')->withTime(),
        ];
    }
    public function getTitle(): string
    {
        return __('model.'.$this->title).': <b>'.$this->getResource()->getItem()->model.'</b>';
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
