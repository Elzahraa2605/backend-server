<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Password Reset OTP - Child Protection')
                    ->html("<h3>Welcome to Child Protection System</h3>
                            <p>You have requested to reset your password.</p>
                            <p>Your One-Time Password (OTP) is: <b style='font-size: 20px; color: #4CAF50;'>{$this->otp}</b></p>
                            <p>This code is valid for 15 minutes only.</p>");
    }
}