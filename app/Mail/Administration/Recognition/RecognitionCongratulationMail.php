<?php

namespace App\Mail\Administration\Recognition;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Recognition\Recognition;

class RecognitionCongratulationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recognition;

    public function __construct(Recognition $recognition)
    {
        $this->recognition = $recognition;
    }

    public function build()
    {
        return $this->subject('Congratulations! You have been recognized')
            ->view('emails.administration.recognition.recognition_congratulation')
            ->with([
                'recognition' => $this->recognition,
            ]);
    }
}
