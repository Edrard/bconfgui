<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Type;
use App\MoonShine\Pages\Type\TypeIndexPage;
use App\MoonShine\Pages\Type\TypeFormPage;
use App\MoonShine\Pages\Type\TypeDetailPage;

use MoonShine\Support\Enums\SortDirection;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

/**
* @extends ModelResource<Type, TypeIndexPage, TypeFormPage, TypeDetailPage>
*/
class TypeResource extends ModelResource
{
    protected string $model = Type::class;

    protected string $title = 'types';
    protected string $column = 'type';
    protected ?string $alias = 'types';

    protected string $sortColumn = 'id';
    protected SortDirection $sortDirection = SortDirection::ASC;

    protected bool $createInModal = false;

    protected bool $editInModal = false;

    protected bool $detailInModal = false;

    /**
    * @return list<Page>
    */
    protected function pages(): array
    {
        return [
            TypeIndexPage::class,
            TypeFormPage::class,
            TypeDetailPage::class,
        ];
    }

    /**
    * @param Type $item
    *
    * @return array<string, string[]|string>
    * @see https://laravel.com/docs/validation#available-validation-rules
    */
    protected function rules(mixed $item): array
    {
        $return['type'][] = 'required';
        if(!$this->getItem()){
            $return['type'][] = 'unique:'.env('DB_CONNECTION_BCONF','mysql_bconf').'.type';
        }
        return $return;
    }
    protected function search(): array
    {
        return [
            'id',
            'type',
            'description',
            'created_at'
        ];
    }
    protected function onLoad(): void
    {
        parent::onLoad();

        $this->title = __('type.'.$this->title);
    }

}
