<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'balance'
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeTransaction()
    {
        return $this->transactions()->whereNull('amount')->first();
    }

    public function getCreditTokenAttribute()
    {
        return optional($this->activeTransaction())->uuid
            ?? Transaction::createCreditToken();
    }

    public function fulfill(int $amount): bool
    {
        if ($this->balance >= $amount) {
            $fulfilled = false;

            DB::transaction(function() use ($amount, &$fulfilled) {
                $this->balance -= $amount;
                $fulfilled = $this->save()
                    && $this->activeTransaction()->update(['amount' => $amount]);
            });

            return $fulfilled;
        }

        return false;
    }
}
