<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('username'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('email_verified_at')
                    ->dateTime(),
                TextEntry::make('role'),
                TextEntry::make('avatar_id')
                    ->numeric(),
                TextEntry::make('cover_id')
                    ->numeric(),
                TextEntry::make('headline'),
                TextEntry::make('location'),
                TextEntry::make('website'),
                IconEntry::make('is_onboarding')
                    ->boolean(),
                TextEntry::make('onboarding_step'),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('last_active_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('stripe_id'),
                TextEntry::make('pm_type'),
                TextEntry::make('pm_last_four'),
                TextEntry::make('trial_ends_at')
                    ->dateTime(),
            ]);
    }
}
