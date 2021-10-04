<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($count, $day)
    {
        $this->count = $count;
        $this->day = $day->format('Y年m月d日');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // 送信元、送信する内容の設定
        return $this->text('emails.email')
                    ->from('noreply@heartsnext.jp', 'heartsnext')
                    ->subject($this->day . 'の日報について')
                    ->with(['count' => $this->count]);
    }
}
