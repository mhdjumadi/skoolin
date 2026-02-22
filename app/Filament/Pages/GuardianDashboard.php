<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Guardian\DailyLessonSchedule;
use App\Filament\Widgets\Guardian\WeeklyStudentAttendance;
use App\Filament\Widgets\Guardian\GuardianStatsOverview;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
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
