<?php

namespace App;

use App\Events\DeviceWasRequested;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscribe()
    {
        $this->subscriptions()->create([
           'item_id' => request('item_id'),
           'subscription_code' => Str::uuid(),
           'item_name' => request('item_name')
        ]);

        return $this;
    }

    public function notify()
    {
        event(new DeviceWasRequested($this->subscriptions()->latest()->first()));
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
