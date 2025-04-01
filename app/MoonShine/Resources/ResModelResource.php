<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Model as EModel;
use App\MoonShine\Pages\Model\ModelIndexPage;
use App\MoonShine\Pages\Model\ModelFormPage;
use App\MoonShine\Pages\Model\ModelDetailPage;

use MoonShine\Support\Enums\SortDirection;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
* @extends ModelResource<Model, ModelIndexPage, ModelFormPage, ModelDetailPage>
*/
class ResModelResource extends ModelResource
{
    protected string $model = EModel::class;
    protected string $sortColumn = 'id';
    protected SortDirection $sortDirection = SortDirection::ASC;


    protected string $title = 'models';
    protected string $column = 'model';
    protected ?string $alias = 'models';

    protected bool $createInModal = false;

    protected bool $editInModal = false;

    protected bool $detailInModal = false;

    /**
    * @return list<Page>
    */
    protected function pages(): array
    {
        return [
            ModelIndexPage::class,
            ModelFormPage::class,
            ModelDetailPage::class,
        ];
    }

    /**
    * @param Group $item
    *
    * @return array<string, string[]|string>
    * @see https://laravel.com/docs/validation#available-validation-rules
    */
    protected function rules(mixed $item): array
    {

        $return['model'][] = 'required';
        if(!$this->getItem()){
            $return['model'][] = 'unique:'.env('DB_CONNECTION_BCONF','mysql_bconf').'.model';
        }
        return $return;
    }
    protected function search(): array
    {
        return [
            'id',
            'model',
            'description',
            'created_at'
        ];
    }
    protected function onLoad(): void
    {
        parent::onLoad();

        $this->title = __('model.'.$this->title);
    }
}
