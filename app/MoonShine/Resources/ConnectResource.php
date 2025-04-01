<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Connect;
use App\MoonShine\Pages\Connect\ConnectIndexPage;
use App\MoonShine\Pages\Connect\ConnectFormPage;
use App\MoonShine\Pages\Connect\ConnectDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Laravel\Pages\Page;

/**
* @extends ModelResource<Connect, ConnectIndexPage, ConnectFormPage, ConnectDetailPage>
*/
class ConnectResource extends ModelResource
{
    protected string $model = Connect::class;

    protected string $sortColumn = 'id';
    protected SortDirection $sortDirection = SortDirection::ASC;

    protected string $title = 'connects';
    protected string $column = 'connect';
    protected ?string $alias = 'connects';

    protected bool $createInModal = false;

    protected bool $editInModal = false;

    protected bool $detailInModal = false;

    /**
    * @return list<Page>
    */
    protected function pages(): array
    {
        return [
            ConnectIndexPage::class,
            ConnectFormPage::class,
            ConnectDetailPage::class,
        ];
    }

    /**
    * @param Connect $item
    *
    * @return array<string, string[]|string>
    * @see https://laravel.com/docs/validation#available-validation-rules
    */
    protected function rules(mixed $item): array
    {
        $return['connect'][] = 'required';
        if(!$this->getItem()){
            $return['connect'][] = 'unique:'.env('DB_CONNECTION_BCONF','mysql_bconf').'.connect';
        }
        return $return;
    }
    protected function search(): array
    {
        return [
            'id',
            'connect',
            'description',
            'created_at'
        ];
    }
    protected function onLoad(): void
    {
        parent::onLoad();

        $this->title = __('connect.'.$this->title);
    }
}
