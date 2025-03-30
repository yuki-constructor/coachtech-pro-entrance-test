<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class ReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $buyer;
    public $seller;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->buyer = $transaction->purchase->user;
        $this->seller = $transaction->purchase->item->user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('取引中の商品について購入者が取引を完了しました')
            ->view('emails.review_notification');
    }
}
