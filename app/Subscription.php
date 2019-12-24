<?php

namespace App;

use App\Events\SubscriptionInitiated;
use App\Events\SubscriptionProcessed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'subscription_code';
    }

    /**
     * Relation.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation.
     *
     * @return HasMany
     */
    public function attendances()
    {
        return $this->hasMany(SubscriptionAttendance::class);
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
     * Inform the subscription for approval.
     *
     * Fires event.
     */
    public function inform()
    {
        event(new SubscriptionProcessed($this));
    }

    /**
     * @return $this
     *
     * @throws \Exception
     */
    public function reject()
    {
        $this->delete();

        return $this;
    }

    /**
     * Revokes the subscription.
     *
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
     * Announces that the subscription was initiated.
     */
    public function announce()
    {
        event(new SubscriptionInitiated($this));
    }

    /**
     * Fetches approved subscriptions for a user.
     *
     * @param $query
     * @param $email
     *
     * @return mixed
     */
    public function scopeApprovedSubscriptionsFor($query, $email)
    {
        return $query->with(['user' => function ($query) use ($email) {
            return $query->where('email', $email);
        }])->whereNotNull('approved_at');
    }

    /*
     * Fetches attendances having out time
     * along with subscriptions
     *
     */

    public function scopeAttendedSubscriptionsFor($query, $email)
    {
        $query->whereHas('attendances', function ($query) use ($email) {
            $query->whereNotNull('out_time');
        })->whereHas('user', function ($q) use ($email) {
            $q->whereEmail($email);
        })->whereNotNull('approved_at');
    }
}
