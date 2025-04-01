<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Config;
use App\MoonShine\Pages\Config\ConfigIndexPage;
use App\MoonShine\Pages\Config\ConfigFormPage;
use App\MoonShine\Pages\Config\ConfigDetailPage;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\FlexibleRender;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Laravel\TypeCasts\ModelCaster;

/**
* @extends ModelResource<Config, ConfigIndexPage, ConfigFormPage, ConfigDetailPage>
*/
class ConfigResource extends ModelResource
{
    protected string $model = Config::class;
    protected string $sortColumn = 'id';
    protected SortDirection $sortDirection = SortDirection::ASC;

    protected string $title = 'Configs';

    /**
    * @return list<Page>
    */
    protected function pages(): array
    {
        return [
            ConfigFormPage::class,
        ];
    }

    /**
    * @param Config $item
    *
    * @return array<string, string[]|string>
    * @see https://laravel.com/docs/validation#available-validation-rules
    */
    protected function rules(mixed $item): array
    {
        return [
        ];
    }
    protected function modifyCreateButton(ActionButtonContract $button): ActionButtonContract
    {
        return $button->canSee(fn() => FALSE);
    }

    protected function specialRules(MoonShineRequest $request): array
    {

        $base = [
            'main|retries' => 'required|numeric|nullable',
            'main|retries_timeout' => 'required|numeric',
            'save|path' => 'required'
        ];

        return $base;

    }

    public function updateSomething(MoonShineRequest $request): MoonShineJsonResponse
    {
        $request->validate($this->specialRules($request));
        foreach ($request->request as $key => $value) {
            if(!preg_match('/^_/', $key)){
                $request->getResource()->getModel()::where('name',$key )->update(['value' => $value || $value == 0 ? $value : '']);
            }
        }

        return MoonShineJsonResponse::make()->toast(__('config.config_request_saved'));
    }
    protected function search(): array
    {
        return [
            'id',
            'name',
            'value',
        ];
    }
}
