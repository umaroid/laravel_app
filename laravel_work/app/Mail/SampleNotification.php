<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SampleNotification extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $title; // メールタイトル
    protected $text; // 差し込み用の文章

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name = 'テスト', $text = 'テストです。')
    {
        $this->title = sprintf("%sさん、ありがとうございます。", $name);
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.sample_notification')
                ->text('emails.sample_notification_plain')
                ->subject($this->title)
                ->with([
                    'text' => $this->text,
                    ]);
    }
}
