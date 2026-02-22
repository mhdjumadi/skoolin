<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DailyLessonSchedule;
use App\Filament\Widgets\WeeklyStudentAttendance;
use App\Filament\Widgets\GuardianStatsOverview;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Dashboard;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class GuardianDashboard extends Page
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected ?string $heading = 'Dashboard Wali';
    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            GuardianStatsOverview::class,
            WeeklyStudentAttendance::class,
            DailyLessonSchedule::class,
        ];
    }
}
