<?php

use Illuminate\Support\Carbon;

if (!function_exists('formatMessageDate')) {
    function formatMessageDate($date) {
        $date = Carbon::parse($date); // Convert to Carbon instance
        $now = Carbon::now();

        if ($date->isToday()) {
            return $date->format('h:i A'); // Example: "12:24 AM"
        } elseif ($date->isYesterday()) {
            return 'Yesterday, ' . $date->format('h:i A'); // Example: "Yesterday, 12:24 AM"
        } elseif ($date->diffInDays($now) < 7) {
            return $date->format('l, h:i A'); // Example: "Tuesday, 12:24 AM"
        } else {
            return $date->format('d F Y, h:i A'); // Example: "25 January 2024, 8:34 AM"
        }
    }
}