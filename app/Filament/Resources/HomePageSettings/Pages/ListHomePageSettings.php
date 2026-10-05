<?php

namespace App\Filament\Resources\HomePageSettings\Pages;

use App\Filament\Resources\HomePageSettings\HomePageSettingResource;
use App\Models\HomePageSetting;
use Filament\Resources\Pages\ListRecords;

class ListHomePageSettings extends ListRecords
{
    protected static string $resource = HomePageSettingResource::class;

    public function mount(): void
    {
        parent::mount();

        $setting = HomePageSetting::firstOrCreate(['id' => 1]);

        $this->redirect(HomePageSettingResource::getUrl('edit', ['record' => $setting->id]));
    }
}
