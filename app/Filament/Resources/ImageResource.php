<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageResource\Pages;
use App\Filament\Resources\ImageResource\RelationManagers;
use App\Forms\Components\DateField;
use App\Forms\Components\ImageField;
use App\Models\Image;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\DeleteAction as TableDeleteAction;
use Filament\Tables\Actions\DeleteBulkAction as TableDeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup as TableBulkActionGroup;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('keyword')->required()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('keyword'),
                TextColumn::make('status')->badge()->colors([
                    'success' => 'completed',
                    'warning' => 'processing',
                    'danger'  => 'failed',
                    'info'    => 'new'
                ]),
                TextColumn::make('progress'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->form([
                    TextInput::make('keyword'),
                    Textarea::make('prompt'),
                    ImageField::make('path')->label('Image'),
                    DateField::make('created_at')->label('Create Date'),
                    DateField::make('updatet_at')->label('Last Modified'),
                ])->modalWidth('2xl')->modalHeading('Show Image'),
                TableDeleteAction::make()->after(function(TableDeleteAction $action){
                   self::deleteFile($action->getRecord()->path);
                }),
            ])
            ->bulkActions([
                TableBulkActionGroup::make([
                    TableDeleteBulkAction::make()->after(function (TableDeleteBulkAction $action){
                        foreach ($action->getRecords() as $record) {
                            self::deleteFile($record->path);
                        }
                    }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])->defaultSort('created_at','desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImages::route('/'),
            // 'create' => Pages\CreateImage::route('/create'),
            // 'edit' => Pages\EditImage::route('/{record}/edit'),
        ];
    }
    
    
    public static function deleteFile($path): void
    {
        if($path && Storage::disk('public')->exists($path)){
            Storage::disk('public')->delete($path);
        }
    }
}
