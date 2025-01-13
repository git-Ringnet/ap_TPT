<?php

namespace App\Console\Commands;

use App\Models\warrantyLookup;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateWanrratyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warranty:update-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Lấy ngày hiện tại
        $today = Carbon::now();

        // Lấy tất cả bản ghi đang trong trạng thái "đang bảo hành"
        $records = WarrantyLookup::all();

        foreach ($records as $record) {
            // Kiểm tra nếu đã hết hạn bảo hành
            if ($today->greaterThanOrEqualTo($record->warranty_expire_date)) {
                $record->update(['status' => 1]); // Cập nhật trạng thái thành "hết bảo hành"
            } else {
                $record->update(['status' => 0]);
            }
        }

        $this->info('Đã cập nhật tình trạng bảo hành.');
    }
}
