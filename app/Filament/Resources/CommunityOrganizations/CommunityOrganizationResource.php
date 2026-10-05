<?php

namespace App\Filament\Resources\CommunityOrganizations;

use App\Filament\Resources\CommunityOrganizations\Pages\CreateCommunityOrganization;
use App\Filament\Resources\CommunityOrganizations\Pages\EditCommunityOrganization;
use App\Filament\Resources\CommunityOrganizations\Pages\ListCommunityOrganizations;
use App\Filament\Resources\CommunityOrganizations\Pages\ViewCommunityOrganization;
use App\Filament\Resources\CommunityOrganizations\Schemas\CommunityOrganizationForm;
use App\Filament\Resources\CommunityOrganizations\Schemas\CommunityOrganizationInfolist;
use App\Models\CommunityOrganization;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommunityOrganizationResource extends Resource
{
    protected static ?string $model = CommunityOrganization::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'COMMUNITY';

    protected static ?string $navigationLabel = 'Organizations';

    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin'])
            || (auth()->user()?->can('view_organizations') ?? false);
    }

    public static function form(Schema $schema): Schema
    {
        return CommunityOrganizationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommunityOrganizationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('translations.name')
                    ->label('Organization Name')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('type.name')
                    ->label('Type')
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Contact Phone')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'pending_review',
                        'success' => 'published',
                        'danger' => 'rejected',
                    ]),

                TextColumn::make('user.name')
                    ->label('Submitted By')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending_review' => 'Pending Review',
                        'published' => 'Published',
                        'rejected' => 'Rejected',
                    ]),

                SelectFilter::make('type_id')
                    ->label('Organization Type')
                    ->relationship('type', 'name'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending_review'
                        && (auth()->user()?->can('approve_organizations') || auth()->user()?->hasRole('super_admin'))
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Approve Community Organization')
                    ->modalDescription('Are you sure you want to approve and list this community organization?')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => $record->published_at ?? now(),
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'rejection_reason' => null,
                        ]);
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending_review'
                        && (auth()->user()?->can('approve_organizations') || auth()->user()?->hasRole('super_admin'))
                    )
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Reason for Rejection')
                            ->required()
                            ->minLength(5)
                            ->maxLength(2000),
                    ])
                    ->modalHeading('Reject Organization Profile')
                    ->modalSubmitActionLabel('Reject Organization')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);
                    }),

                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommunityOrganizations::route('/'),
            'create' => CreateCommunityOrganization::route('/create'),
            'view' => ViewCommunityOrganization::route('/{record}'),
            'edit' => EditCommunityOrganization::route('/{record}/edit'),
        ];
    }
}
