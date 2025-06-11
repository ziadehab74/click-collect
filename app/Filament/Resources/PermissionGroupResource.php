<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionGroupResource\Pages;
use App\Filament\Resources\PermissionGroupResource\RelationManagers;
use App\Models\PermissionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionGroupResource extends Resource
{
    protected static ?string $model = PermissionGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'System Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('created_by')
                //     ->numeric(),
                // Forms\Components\TextInput::make('updated_by')
                //     ->numeric(),
                // Forms\Components\TextInput::make('deleted_by')
                //     ->numeric(),
                // Forms\Components\DateTimePicker::make('restored_at'),
                // Forms\Components\TextInput::make('restored_by')
                //     ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(255),
                // Forms\Components\TextInput::make('color')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('redirect')
                //     ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->unique(ignoreRecord: true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('created_by')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_by')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('deleted_by')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('restored_at')
                //     ->dateTime()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('restored_by')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('color')
                    // ->searchable(),
                // Tables\Columns\TextColumn::make('redirect')
                    // ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPermissionGroups::route('/'),
            'create' => Pages\CreatePermissionGroup::route('/create'),
            'view' => Pages\ViewPermissionGroup::route('/{record}'),
            'edit' => Pages\EditPermissionGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
