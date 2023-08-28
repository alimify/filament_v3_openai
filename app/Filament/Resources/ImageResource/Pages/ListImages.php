<?php

namespace App\Filament\Resources\ImageResource\Pages;

use App\Filament\Resources\ImageResource;
use App\Jobs\GenerateImageJob;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;

class ListImages extends ListRecords
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function(CreateAction $action){
                GenerateImageJob::dispatch($action->getRecord())->delay(Carbon::now()->addSeconds(3));
            }),
        ];
    }
}
