<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Chạy tự động kiểm tra và hủy đơn hàng quá hạn mỗi 10 phút
Schedule::command('orders:cancel-unpaid')->everyTenMinutes();

