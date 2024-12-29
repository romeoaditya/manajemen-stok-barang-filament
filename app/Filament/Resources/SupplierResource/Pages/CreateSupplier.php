<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;
    protected function getRedirectUrl(): string
        {
            // Redirect ke halaman index setelah create
            return $this->getResource()::getUrl('index');
        }
}
