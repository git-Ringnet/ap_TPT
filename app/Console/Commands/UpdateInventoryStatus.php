<?php

namespace App\Console\Commands;

use App\Models\InventoryLookup;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateInventoryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thời gian tồn kho của Inventory Lookup mỗi ngày';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Lấy tất cả các bản ghi trong InventoryLookup
        $records = InventoryLookup::all();

        foreach ($records as $record) {
            $record->increment('storage_duration');
            $record->save();

            // Kiểm tra nếu storage_duration >= 90
            if ($record->storage_duration >= 90) {
                // Lấy ngày hiện tại
                $today = Carbon::now()->format('Y-m-d');

                // Kiểm tra thông báo đã tồn tại trong cùng ngày, cùng type và type_id
                $exists = Notification::where('type', '1')
                    ->where('type_id', $record->id)
                    ->whereDate('created_at', $today)
                    ->exists();

                // Nếu chưa tồn tại, thêm mới thông báo
                if (!$exists) {
                    Notification::create([
                        'type' => '1',
                        'type_id' => $record->id,
                        'status' => '1',
                    ]);
                }
            }
        }

        $this->info('Đã cập nhật thời gian tồn kho cho tất cả các sản phẩm.');
    }
}
