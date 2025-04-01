<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

use MoonShine\UI\Components\ActionButton;
use MoonShine\Support\ListOf;
use MoonShine\AssetManager\Js;

use App\Models\DevicesConfig;
use App\MoonShine\Pages\DeviceConfig\DeviceConfigIndexPage;
use App\MoonShine\Pages\DeviceConfig\DeviceConfigFormPage;
use App\MoonShine\Pages\DeviceConfig\DeviceConfigDetailPage;
use App\Helpers\HelperRequest;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Enums\SortDirection;

use MoonShine\Core\Collections\Components;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Components\Icon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Select;
use App\MoonShine\Resources\ConnectResource;
use App\MoonShine\Resources\GroupResource;
use App\MoonShine\Resources\TypeResource;
use App\MoonShine\Resources\ResModelResource;
use MoonShine\AssetManager\InlineJs;
use MoonShine\Support\DTOs\AsyncCallback;
use MoonShine\Laravel\TypeCasts\ModelCaster;

/**
* @extends ModelResource<DeviceConfig, DeviceConfigIndexPage, DeviceConfigFormPage, DeviceConfigDetailPage>
*/
class DeviceConfigResource extends ModelResource
{
    protected string $model = DevicesConfig::class;
    protected string $sortColumn = 'name';
    protected SortDirection $sortDirection = SortDirection::ASC;

    protected string $title = 'device_configs';

    protected string $column = 'device_config';
    protected ?string $alias = 'device_configs';
    protected bool $isLazy = true;
    protected bool $usePagination = true;
    protected int $itemsPerPage = 15;
    protected bool $saveQueryState = true;
    protected array $with = ['group', 'model', 'type'];

    protected bool $createInModal = false;

    protected bool $editInModal = false;

    protected bool $detailInModal = false;

    /**
    * @return list<Page>
    */
    protected function pages(): array
    {
        return [
            DeviceConfigIndexPage::class,
            DeviceConfigFormPage::class,
            DeviceConfigDetailPage::class,
        ];
    }

    /**
    * @param DeviceConfig $item
    *
    * @return array<string, string[]|string>
    * @see https://laravel.com/docs/validation#available-validation-rules
    */
    protected function rules(mixed $item): array
    {
        return [];
    }
    protected function search(): array
    {
        return [
            'id',
            'ip',
            'name'
        ];
    }
    protected function onLoad(): void
    {
        parent::onLoad();

        $this->title = __('device.'.$this->title);
    }
    protected function filters(): iterable
    {
        return [
            Text::make(__('device.name'), 'name'),
            Text::make(__('device.ip'), 'ip'),
            BelongsTo::make('device.group', 'group', resource: GroupResource::class)
            ->nullable()
            ->translatable()
            ->native()
            ->valuesQuery(fn(Builder $query, BelongsTo $field) => $query->orderBy('group'))
            ->onApply(function (Builder $query, mixed $value, BelongsTo $field) {
                if($value){
                    $query->where('group_id', $value);
                }
            }),
            BelongsTo::make('device.type', 'type', resource: TypeResource::class)
            ->translatable()
            ->nullable()
            ->native()
            ->valuesQuery(fn(Builder $query, BelongsTo $field) => $query->orderBy('type'))
            ->onApply(function (Builder $query, mixed $value, BelongsTo $field) {
                if($value){
                    $query->where('type_id', $value);
                }
            }),
            BelongsTo::make('device.model', 'model', resource: ResModelResource::class)
            ->translatable()
            ->nullable()
            ->native()
            ->valuesQuery(fn(Builder $query, BelongsTo $field) => $query->orderBy('model'))
            ->onApply(function (Builder $query, mixed $value, BelongsTo $field) {
                if($value){
                    $query->where('model_id', $value);
                }
            }),
            Select::make('device.status','status')
            ->options([
                1 => 'Active',
                0 => 'Passive',
            ])
            ->multiple(false)
            ->translatable()
            ->nullable()
            ->native()
            ->onApply(function (Builder $query, mixed $value, Select $field) {
                in_array($value,[1,0])
                ? $query->where('status', $value)
                : $query->where('status', 'like', '%');

            })
            ,
        ];
    }
    protected function formButtons(): ListOf
    {
        return parent::formButtons()
        ->add(
            ActionButton::make('Password')
            ->setAttribute('onclick', 'unhidePassword()')
        )->prepend(
            ActionButton::make(fn(ActionButton $item) => $item->getData()->toArray()['status'] == 1 ? __('device.enable') : __('device.disable'))
            ->method(
                'updateStatus',
            )
            ->async(
                callback: AsyncCallback::with(responseHandler: 'myFunction')
            )
            ->onBeforeSet(function(?DataWrapperContract $data, ActionButton $ctx){
                $color = $data->toArray()['status'] == 1 ? 'btn-success' : 'btn-error';
                $ctx->class([$color]);
                return $data;
            })
        );
    }
    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
        ->prepend(
            ActionButton::make(fn(ActionButton $item) => $item->getData()->toArray()['status'] == 1 ? __('device.enable') : __('device.disable'))
            ->method(
                'updateStatus',
            )
            ->async(
                events: [
                    AlpineJs::event(JsEvent::TABLE_UPDATED, $this->getListComponentName())
                ]
            )->onBeforeSet(function(?DataWrapperContract $data, ActionButton $ctx){
                $color = $data->toArray()['status'] == 1 ? 'btn-success' : 'btn-error';
                $ctx->class([$color]);
                return $data;
            })
        );
    }
    protected function detailButtons(): ListOf
    {
        return parent::detailButtons()
        ->prepend(
            ActionButton::make(fn(ActionButton $item) => $item->getData()->toArray()['status'] == 1 ? __('device.enable') : __('device.disable'))
            ->method(
                'updateStatus',
            )
            ->async(
                callback: AsyncCallback::with(responseHandler: 'myFunction')
            )
            ->onBeforeSet(function(?DataWrapperContract $data, ActionButton $ctx){
                $color = $data->toArray()['status'] == 1 ? 'btn-success' : 'btn-error';
                $ctx->class([$color]);
                return $data;
            })
        );
    }
    public function updateStatus(MoonShineRequest $request): MoonShineJsonResponse
    {

        $items = $request->getResource()->getModel()::where('id',$request->query()['resourceItem'])->first();
        $items->status = $items->status == 1 ? 0 : 1;
        $items->save();

        return MoonShineJsonResponse::make()->toast(__( $items->status == 1 ? __('device.enabled') : __('device.disabled')));
    }
    /*public function getItems(): Collection|LazyCollection|CursorPaginator|Paginator
    {
    return $this->getModel()::with(['group', 'type', 'model'])->get();;

    }      */
    protected function assets(): array
    {
        return [
            Js::make('/vendor/js/test.js')
        ];
    }
    protected function specialRules(array $data): array
    {
        $base = [
            'ip' => 'required',
            'port' => 'required',
            'config_search' => 'required'
        ];
        $base['name'][] = 'required';
        if(!$data['id'] || !is_numeric($data['id'])){
            $base['name'][] = 'unique:'.env('DB_CONNECTION_BCONF','mysql_bconf').'.devices_config';
        }
        return $base;

    }
    public function updateDevice(MoonShineRequest $request): MoonShineJsonResponse
    {
        $data = $request->all();
        $request->validate($this->specialRules($data));
        $device = $this->getModel()::findOrNew($data['id']);

        $device->fill(HelperRequest::cleans($data,['id','method']));
        $device->save();

        return MoonShineJsonResponse::make()->toast(__('device.device_saved'));
    }
}
