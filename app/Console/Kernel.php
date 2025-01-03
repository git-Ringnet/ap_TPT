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
        // Lệnh tự động tăng thời gian tồn kho mỗi ngày Test khi hết test đổi thành ngày
        $schedule->command('inventory:update-storage')->everyMinute();
        $schedule->command('warranty:update-storage')->everyMinute();
        $schedule->command('receiving:update-status')->everyMinute();
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
