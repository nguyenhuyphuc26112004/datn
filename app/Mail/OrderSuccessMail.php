<?php

namespace App\Mail;

use App\Models\DonHang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $donHang;

    /**
     * Create a new message instance.
     */
    public function __construct(DonHang $donHang)
    {
        $this->donHang = $donHang;
    }

    public function build()
    {
        return $this->subject("Đặt hàng thành công #" . $this->donHang->id)
                    ->view('client.emails.order_success');
    }
}