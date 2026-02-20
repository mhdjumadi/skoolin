<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\CurrentLessonBanner;
use App\Filament\Widgets\MonthlyAttendanceChart;
use App\Filament\Widgets\StudentsNotYetAttended;
use App\Filament\Widgets\TeacherAttendanceWeeklyChart;
use App\Filament\Widgets\TeacherClassAttendance;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    protected function getHeaderWidgets(): array
    {
        return [
            CurrentLessonBanner::class,
            AdminStatsOverview::class,
            MonthlyAttendanceChart::class,
            TeacherAttendanceWeeklyChart::class,
            TeacherClassAttendance::class,
            StudentsNotYetAttended::class,
        ];
    }
}
