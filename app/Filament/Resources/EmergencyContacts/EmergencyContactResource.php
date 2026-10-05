<?php

namespace App\Filament\Resources\EmergencyContacts;

use App\Filament\Resources\EmergencyContacts\Pages\CreateEmergencyContact;
use App\Filament\Resources\EmergencyContacts\Pages\EditEmergencyContact;
use App\Filament\Resources\EmergencyContacts\Pages\ListEmergencyContacts;
use App\Filament\Resources\EmergencyContacts\Pages\ViewEmergencyContact;
use App\Filament\Resources\EmergencyContacts\Schemas\EmergencyContactForm;
use App\Filament\Resources\EmergencyContacts\Schemas\EmergencyContactInfolist;
use App\Filament\Resources\EmergencyContacts\Tables\EmergencyContactsTable;
use App\Models\EmergencyContact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmergencyContactResource extends Resource
{
    protected static ?string $model = EmergencyContact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|\UnitEnum|null $navigationGroup = 'EMERGENCY';

    protected static ?string $navigationLabel = 'Emergency Contacts';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return EmergencyContactForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmergencyContactInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmergencyContactsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmergencyContacts::route('/'),
            'create' => CreateEmergencyContact::route('/create'),
            'view' => ViewEmergencyContact::route('/{record}'),
            'edit' => EditEmergencyContact::route('/{record}/edit'),
        ];
    }
}
