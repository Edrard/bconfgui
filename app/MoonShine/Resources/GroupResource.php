<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\MoonShine\Pages\Group\GroupIndexPage;
use App\MoonShine\Pages\Group\GroupFormPage;
use App\MoonShine\Pages\Group\GroupDetailPage;

use MoonShine\Support\Enums\SortDirection;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;

use MoonShine\Core\Collections\Components;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Fields\Text;
use MoonShine\Contracts\UI\ActionButtonContract;

/**
* @extends ModelResource<Group, GroupIndexPage, GroupFormPage, GroupDetailPage>
*/
class GroupResource extends ModelResource
{
    protected string $model = Group::class;
    protected string $sortColumn = 'group';
    protected SortDirection $sortDirection = SortDirection::ASC;


    protected string $title = 'groups';
    protected string $column = 'group';
    protected ?string $alias = 'groups';

    protected bool $createInModal = false;

    protected bool $editInModal = false;

    protected bool $detailInModal = false;


    /**
    * @return list<Page>
    */
    protected function pages(): array
    {
        return [
            GroupIndexPage::class,
            GroupFormPage::class,
            GroupDetailPage::class,
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

        $return['group'][] = 'required';
        if(!$this->getItem()){
            $return['group'][] = 'unique:'.env('DB_CONNECTION_BCONF','mysql_bconf').'.group';
        }
        return $return;
    }
    protected function search(): array
    {
        return [
            'id',
            'group',
            'description',
            'created_at'
        ];
    }
    protected function onLoad(): void
    {
        parent::onLoad();

        $this->title = __('group.'.$this->title);
    }
}
