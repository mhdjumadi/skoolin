<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Admin\AdminStatsOverview;
use App\Filament\Widgets\Admin\CurrentLessonBanner;
use App\Filament\Widgets\Admin\StudentsNotYetAttended;
use App\Filament\Widgets\Admin\TeacherAttendanceWeeklyChart;
use App\Filament\Widgets\Admin\TeacherClassAttendance;
use App\Filament\Widgets\Admin\WeeklyAttendanceChart;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class AdminDashboard extends Page
{
    use HasPageShield;

    protected string $view = 'filament.pages.admin-dashboard';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    protected function getHeaderWidgets(): array
    {
        return [
            CurrentLessonBanner::class,
            AdminStatsOverview::class,
            WeeklyAttendanceChart::class,
            TeacherAttendanceWeeklyChart::class,
            TeacherClassAttendance::class,
            StudentsNotYetAttended::class,
        ];
    }
}
