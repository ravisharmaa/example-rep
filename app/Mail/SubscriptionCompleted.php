<?php

namespace App\Mail;

use App\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCompleted extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $subscription;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->subscription->approved_at) {
            return $this->subject('Subscription Approved')->markdown('emails.subscription-completed');
        }

        return $this->subject('Subscription Rejected')->markdown('emails.subscription-rejected');
    }
}
