<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageResource\Pages;
use App\Filament\Resources\ImageResource\RelationManagers;
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
use Tables\Actions\DeleteAction as TableDeleteAction;

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
                    'danger' => 'new'
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
                    ViewField::make('path')->label('Image')
                                           ->view('forms.components.image-field'),
                    ViewField::make('created_at')->label('Create Date')
                                                 ->view('forms.components.date-field'),
                    ViewField::make('updatet_at')->label('Last Modified')
                                                 ->view('forms.components.date-field'),
                ])->modalWidth('2xl')->modalHeading('Show Image'),
                
                Tables\Actions\DeleteAction::make()->after(function(Tables\Actions\DeleteAction $action){
                    if($action->getRecord()->path && Storage::disk('public')->exists($action->getRecord()->path)){
                        Storage::disk('public')->delete($action->getRecord()->path);
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (Tables\Actions\DeleteBulkAction $action){
                        foreach ($action->getRecords() as $record) {
                            if($record->path && Storage::disk('public')->exists($record->path)){
                                Storage::disk('public')->delete($record->path);
                            }
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
}
