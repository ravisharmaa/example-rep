<?php

namespace App;

use App\Events\SubscriptionInitiated;
use App\Events\SubscriptionProcessed;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'subscription_code';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return $this
     */
    public function approve()
    {
        $this->update([
            'approved_by' => $this->user->department->head,
            'approved_at' => now(),
        ]);

        return $this;
    }

    /**
     * Fires event
     */
    public function inform()
    {
        event(new SubscriptionProcessed($this));
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function reject()
    {
        $this->delete();

        return $this;
    }

    /**
     * @return $this
     */
    public function revoke()
    {
        $this->update([
           'returned_at' => now(),
        ]);

        return $this;
    }

    /**
     * Announce
     */
    public function announce()
    {
        event(new SubscriptionInitiated($this));
    }

    /**
     *
     */

    public function attendances()
    {
        return $this->hasMany(SubscriptionAttendance::class);
    }


}
