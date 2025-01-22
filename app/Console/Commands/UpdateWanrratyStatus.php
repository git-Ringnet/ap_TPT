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
            if ($today->greaterThanOrEqualTo($record->warranty_expire_date)) {
                $status = $record->name_warranty . ' hết bảo hành';
                $record->update(['status' => $status]);

                // Kiểm tra và cập nhật các bản ghi có sn_id giống với bản ghi hiện tại
                WarrantyLookup::where('sn_id', $record->sn_id)
                    ->where('id', '!=', $record->id)  // Đảm bảo không cập nhật chính nó
                    ->update(['status' => $status]);
            } else {
                $record->update(['status' => "Còn bảo hành"]);
            }
        }

        $this->info('Đã cập nhật tình trạng bảo hành.');
    }
}
