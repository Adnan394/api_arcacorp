<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KirimNotifikasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $category;
    public $amount;

    public function __construct($nama, $category, $amount)
    {
        $this->nama = $nama;
        $this->category = $category;
        $this->amount = $amount;
    }

    public function build()
    {
        return $this->subject('Notifikasi Baru')
                    ->view('emails.notifikasi', [
                        'nama' => $this->nama,
                        'category' => $this->category,
                        'amount' => $this->amount,
                    ])->with([
                        'nama' => $this->nama,
                        'category' => $this->category,
                        'amount' => $this->amount,
                    ]);
    }
}