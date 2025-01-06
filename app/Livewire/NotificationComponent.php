<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationComponent extends Component
{
    public $notifications = [];
    public $isListNotiVisible = false;

    public function fetchNotifications()
    {
        // Lấy thông báo từ database
        $this->notifications = Notification::where('type', 1)
            ->leftJoin("inventory_lookup", "notification.type_id", "inventory_lookup.id")
            ->leftJoin("serial_numbers", "inventory_lookup.sn_id", "serial_numbers.id")
            ->select("inventory_lookup.id", "serial_numbers.serial_code")
            ->get();
    }

    public function mount()
    {
        // Lấy thông báo ngay khi component được tải
        $this->fetchNotifications();
    }

    public function render()
    {
        return view('livewire.notification-component');
    }
}
