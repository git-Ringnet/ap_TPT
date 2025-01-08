<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Receiving;
use App\Models\User;
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

        // Các trạng thái để so sánh
        $states = [
            '0' => 'tiếp nhận',
            '1' => 'chưa xử lý',
            '2' => 'quá hạn',
        ];

        // Tiếp nhận (< 3 ngày, state = 0)
        Receiving::where('status', 1)
            ->where('date_created', '>=', $today->copy()->subDays(3))
            ->get()
            ->each(function ($receiving) use ($states) {
                $oldState = $states[$receiving->state] ?? 'Không xác định';
                $newState = $states[0];
                $receiving->update(['state' => 0]);

                // Gửi thông báo
                $this->notifyStateChange($receiving, $oldState, $newState);
            });

        // Chưa xử lý (>= 3 ngày, state = 1)
        Receiving::where('status', 1)
            ->where('date_created', '<', $today->copy()->subDays(3))
            ->where('date_created', '>=', $today->copy()->subDays(21))
            ->get()
            ->each(function ($receiving) use ($states) {
                $oldState = $states[$receiving->state] ?? 'Không xác định';
                $newState = $states[1];
                $receiving->update(['state' => 1]);

                // Gửi thông báo
                $this->notifyStateChange($receiving, $oldState, $newState);
            });

        // Quá hạn (>= 21 ngày, state = 2)
        Receiving::whereNotIn('status', [3, 4])
            ->where('date_created', '<', $today->copy()->subDays(21))
            ->get()
            ->each(function ($receiving) use ($states) {
                $oldState = $states[$receiving->state] ?? 'Không xác định';
                $newState = $states[2];
                $receiving->update(['state' => 2]);

                // Gửi thông báo
                $this->notifyStateChange($receiving, $oldState, $newState);
            });

        // Hoàn thành hoặc khách không đồng ý
        Receiving::whereIn('status', [3, 4])
            ->update(['state' => 0]);

        $this->info('Receiving statuses updated successfully.');
        return Command::SUCCESS;
    }

    private function notifyStateChange($receiving, $oldState, $newState)
    {
        $users = User::all(); // Lọc user cần thiết nếu muốn
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\ReceiNotification($receiving, $oldState, $newState));
        }
    }
}
