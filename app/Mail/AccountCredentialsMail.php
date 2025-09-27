<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $accountNumber;
    public $plainPassword;

    public function __construct($user, $accountNumber, $plainPassword)
    {
        $this->user = $user;
        $this->accountNumber = $accountNumber;
        $this->plainPassword = $plainPassword;
    }

    public function build()
{
    return $this->subject('Your Account Credentials')
                ->view('emails.account_credentials')
                ->with([
                    'user' => $this->user,
                    'accountNumber' => $this->accountNumber,
                    'plainPassword' => $this->plainPassword,
                ]);
}

}
