<?php

namespace App\Mail;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable
{
    use Queueable, SerializesModels;

    private $data = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        try{

            $email = $this->data['email'];
            return $this->from($email)
                       ->subject($this->data['subject'] ?? 'DEFAULT SUBJECT')
                       ->view('email')
                       ->with([
                        'title' => $this->data['title'] ?? '',
                        'body' => $this->data['body'] ?? '',
            ]);
        }catch(Exception $e){
            return response()->json([
                'response_code' => 500,
                'response_msg' => "Error",
                'response_data' => [
                    'status' => 'Error',
                    'message' => $e->getMessage()
                ],
            ], 500);
        }
    }

}
