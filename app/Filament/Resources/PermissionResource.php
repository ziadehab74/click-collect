<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'System Management';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Select::make('role_id')
                    ->label('Role')
                    ->relationship('role', 'name')
                    ->required(),
                // ->hidden(), // Hides it but still allows selection

                // Group Permission Select
                Select::make('group_id')
                    ->label('Group Permission')
                    ->relationship('group', 'name')
                    ->preload()
                    ->required()
                    ->reactive(), // Make it reactive to update the next select field

                // Module Select (Dependent on group_id)
                Select::make('module_id')
                    ->label('Module')
                    ->relationship('module', 'name')
                    ->preload()
                    ->required()
                    ->reactive()
                                        ->disabled(fn(callable $get) => !$get('category_id')) // Disable if no category is selected

                    ->options(function (callable $get) {
                        $groupId = $get('group_id'); // Get selected group_id
                        if (!$groupId) {
                            return []; // If no group is selected, return empty options
                        }
                        return Module::where('group_id', $groupId)
                            ->pluck('name', 'id')
                            ->toArray();
                    }),

                // Multi-checkbox Permissions
                CheckboxList::make('view')
                    ->label('Permissions')
                    ->options([
                        'create'  => 'Create',
                        'update'  => 'Update',
                        'delete'  => 'Delete',
                        'view'    => 'View',
                        'trashed' => 'Trashed',
                    ])
                    ->columns(3)
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.name')
                    ->label('module')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('role.name')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name') // Display permission name
                    ->label('Permission')
                    ->searchable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'view' => Pages\ViewPermission::route('/{record}'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
