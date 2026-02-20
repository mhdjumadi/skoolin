<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            ['name' => 'Senin', 'code' => 'MON', 'order' => 1],
            ['name' => 'Selasa', 'code' => 'TUE', 'order' => 2],
            ['name' => 'Rabu', 'code' => 'WED', 'order' => 3],
            ['name' => 'Kamis', 'code' => 'THU', 'order' => 4],
            ['name' => 'Jumat', 'code' => 'FRI', 'order' => 5],
            ['name' => 'Sabtu', 'code' => 'SAT', 'order' => 6],
            ['name' => 'Minggu', 'code' => 'SUN', 'order' => 7],
        ];

        foreach ($days as $day) {
            Day::updateOrCreate(
                ['order' => $day['order']],
                $day
            );
        }
    }
}
