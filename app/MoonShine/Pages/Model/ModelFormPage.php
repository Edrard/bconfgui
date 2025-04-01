<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Model;

use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Collections\Fields;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Fields\Hidden;
use Throwable;


class ModelFormPage extends FormPage
{
    protected string $title = 'm_edit';

    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Text::make(__('elem.name'), 'model'),
                Textarea::make(__('elem.desc'), 'description')->customAttributes([
                    'rows' => 6,
                ]),
            ]),
        ];
    }                         //ui.resource.search
    protected function getFormComponent(
        string $action,
        ?DataWrapperContract $item,
        Fields $fields,
        bool $isAsync = true,
    ): ComponentContract
    {
        $name = 'elem.create';
        if($this->getResource()->getItem()?->model) {
            $name = 'elem.save';
        }

        return (parent::getFormComponent($action,$item,$fields,$isAsync)->submit(__($name), ['class' => 'btn-primary']));

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
    public function getTitle(): string
    {
        $title = __('model.'.$this->title.'_c');
        if($this->getResource()->getItem()?->model){
            $title = __('model.'.$this->title).': <b>'.$this->getResource()->getItem()?->model.'</b>';
        }

        return $title;
    }
    public function getBreadcrumbs(): array
    {
        if(!$this->getResource()->getItem()?->model){
            $ret['model-index-page'] = __('model.models');
            $ret['#'] = __('model.'.$this->title.'_c');
            return $ret;
        }

        return parent::getBreadcrumbs();
    }
}
