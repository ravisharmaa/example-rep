<?php

namespace App\Mail;

use App\Subscription;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionPrepared extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     *@var User
     */
    public $user;

    /**
     * @var Subscription
     */
    public $subscription;

    /**
     * Create a new message instance.
     * @param Subscription $subscription
     * @param User|null $user
     */
    public function __construct(Subscription $subscription, ?User $user = null)
    {
        $this->subscription = $subscription;
        $this->user = auth()->user();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->subscription->returned_at) {
            return $this->subject('Item Returned')->markdown('emails.item-returned');
        }

        return $this->markdown('emails.subscription-prepared');
    }
}
