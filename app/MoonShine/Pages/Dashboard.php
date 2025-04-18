<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\FormBuilder;
use App\Models\Config;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Laravel\TypeCasts\ModelCaster;
use MoonShine\UI\Fields\Text;
use Symfony\Component\HttpFoundation\Response;

class Dashboard extends Page
{
    /**
    * @return array<string, string>
    */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    private function getSetting(): Config
    {
        return Config::query()->find(1);
    }

    public function store(): MoonShineJsonResponse
    {
        $this->form()->apply(fn(Config $item) => $item->save());

        return MoonShineJsonResponse::make()->toast('Saved');
    }
    protected function modifyResponse(): ?Response
    {
        return redirect()->to('/device_configs/device-config-index-page');
    }
    private function form(): FormBuilder
    {
        return FormBuilder::make()
        ->asyncMethod('store')
        ->fillCast($this->getSetting(), new ModelCaster(Config::class))
        ->fields([
            Text::make(__('elem.name'), 'value'),
            Text::make(__('elem.desc'), 'name'),
        ])
        ;
    }

    protected function components(): iterable
    {
        yield $this->form();
    }
}
