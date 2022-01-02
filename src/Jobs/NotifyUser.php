<?php

namespace Nour\Export\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Nour\Export\Mail\QueueEmail;

class NotifyUser implements ShouldQueue
{
    use Queueable, Batchable, SerializesModels;


    private string $message;
    private string $mailTo;

    public function __construct($message, $mailTo)
    {
        $this->message = $message;
        $this->mailTo = $mailTo;
    }

    public function handle()
    {
        if ($this->mailTo !== '') {
            Mail::to($this->mailTo)
                ->send(new QueueEmail($this->message));
        }
    }
}
