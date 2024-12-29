<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;
    protected function getRedirectUrl(): string
        {
            // Redirect ke halaman index setelah create
            return $this->getResource()::getUrl('index');
        }
}
