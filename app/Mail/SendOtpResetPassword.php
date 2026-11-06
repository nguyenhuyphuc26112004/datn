<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $hoTen;

    public function __construct($otp, $hoTen)
    {
        $this->otp = $otp;
        $this->hoTen = $hoTen;
    }

    public function build()
    {
        return $this->subject('Mã OTP xác nhận quên mật khẩu')
                    ->view('client.emails.otp_reset');
    }
}