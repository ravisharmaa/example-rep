<?php

namespace App\Mail;

use App\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionApproved extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Device
     */
    public $device;
    public $approvedBy;

    /**
     * Create a new message instance.
     *
     * @param Device $device
     * @param $approvedBy
     */
    public function __construct(Device $device, $approvedBy)
    {
        $this->device = $device;
        $this->approvedBy = $approvedBy;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.subscription-approved');
    }
}
