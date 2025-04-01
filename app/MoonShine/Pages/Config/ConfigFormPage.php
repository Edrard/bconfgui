<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Config;

use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Components\Layout\Divider;
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
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use Throwable;


class ConfigFormPage extends FormPage
{
    protected string $title = 'conf_edit';
    /**
    * @return list<ComponentContract|FieldContract>
    */
    protected function fields(): iterable
    {
        $res = $this->getResource()->getModel()->all()->keyBy('name');
        return [
            Divider::make(__('config.system_head')),
            Grid::make([
                Column::make(
                    [
                        //Heading::make('Custom main'),
                        Switcher::make('config.override', 'override')->setValue($res['override']->value)->translatable()->hint(__('config.override_desc')),
                        Text::make(__('config.save|path'), "save|path")->setValue($res['save|path']->value)->translatable()->hint(__('config.save|path_desc')),
                    ],
                    colSpan: 8,
                    adaptiveColSpan: 12
                )
            ]),
            Divider::make(__('config.disable_head')),
            Grid::make([
                Column::make(
                    [
                        Switcher::make('config.disable|dumping', 'disable|dumping')->setValue($res['disable|dumping']->value)->translatable()->hint(__('config.disable|dumping_desc')),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        Switcher::make('config.disable|saving', 'disable|saving')->setValue($res['disable|saving']->value)->translatable()->hint(__('config.disable|saving_desc')),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
            ]),
            Divider::make(__('config.retries_head')),
            Grid::make([
                Column::make(
                    [
                        Text::make(__('config.main|retries'), "main|retries")->setValue($res['main|retries']->value)->translatable()->hint(__('config.main|retries_desc')),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        Text::make(__('config.main|retries_timeout'), "main|retries_timeout")->setValue($res['main|retries_timeout']->value)->translatable()->hint(__('config.main|retries_timeout_desc')),
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

        return (parent::getFormComponent($action,$item,$fields,$isAsync)->submit(__('elem.save'))->asyncMethod('updateSomething'));

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
            //Heading::make('Custom main'),
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
        $title = __('config.'.$this->title.'_c');
        if($this->getResource()->getItem()?->group){
            $title = __('config.'.$this->title).': <b>'.$this->getResource()->getItem()?->group.'</b>';
        }

        return $title;
    }
    public function getBreadcrumbs(): array
    {
        if(!$this->getResource()->getItem()?->group){
            $ret['config-form-page'] = __('config.configs');
            $ret['#'] = __('config.'.$this->title.'_c');
            return $ret;
        }

        return parent::getBreadcrumbs();
    }
}
