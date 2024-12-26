<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Receiving;
use Carbon\Carbon;

class UpdateReceivingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receiving:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of receiving records daily based on date_created and conditions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now();

        // Tiếp nhận (< 3 ngày, state = 0)
        Receiving::where('status', 1)
            ->where('date_created', '>=', $today->copy()->subDays(3))
            ->update(['state' => 0]);

        // Chưa xử lý (>= 3 ngày, state = 1)
        Receiving::where('status', 1)
            ->where('date_created', '<', $today->copy()->subDays(3))
            ->where('date_created', '>=', $today->copy()->subDays(21))
            ->update(['state' => 1]);

        // Quá hạn (>= 21 ngày, state = 2)
        Receiving::whereNotIn('status', [3, 4]) // Không hoàn thành hoặc khách không đồng ý
            ->where('date_created', '<', $today->copy()->subDays(21))
            ->update(['state' => 2]);
        // Kiểm tra nếu có phiếu trả hàng rồi thì cập nhật trạng thái về trắng
        Receiving::whereIn('status', [3, 4])
            ->update(['state' => 0]);

        $this->info('Receiving statuses updated successfully.');
        return Command::SUCCESS;
    }
}
