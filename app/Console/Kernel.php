<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Đăng ký các lệnh Artisan của bạn.
     */
    protected $commands = [
        //
    ];

    /**
     * Định nghĩa lịch trình cho các lệnh Artisan.
     */
    protected function schedule(Schedule $schedule)
    {
        // Lệnh tự động tăng thời gian tồn kho mỗi ngày
        $schedule->command('inventory:update-storage')->dailyAt('00:00');
        $schedule->command('warranty:update-storage')->dailyAt('00:00');
    }

    /**
     * Đăng ký các lệnh của ứng dụng.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
