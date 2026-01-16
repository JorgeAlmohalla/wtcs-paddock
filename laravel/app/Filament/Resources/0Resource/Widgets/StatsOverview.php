<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\IncidentReport;
use App\Models\Race;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Drivers', User::whereJsonContains('roles', 'driver')->count())
                ->description('Active drivers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            
            Stat::make('Pending Reports', IncidentReport::where('status', 'pending')->count())
                ->description('Needs review')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Next Race', Race::where('status', 'scheduled')->orderBy('race_date')->first()?->track->name ?? 'None')
                ->description('Upcoming event')
                ->color('primary'),
        ];
    }
}