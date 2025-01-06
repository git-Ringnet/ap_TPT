<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationComponent extends Component
{
    public $notifications = [];

    public function fetchNotifications()
    {
        // Lấy thông báo từ database
        $this->notifications = Notification::latest()->get();
    }

    public function render()
    {
        return view('livewire.notification-component');
    }
}
