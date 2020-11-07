<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreditTokenAttribute()
    {
        return $this->token . '-' .  base64_encode($this->id);
    }

    public static function createCreditToken(string $token = null, int $amount = null): string
    {
        return self::query()->create([
            'user_id' => Auth::id(),
            'token' => $token ?? Str::uuid(),
            'amount' => $amount
        ])->creditToken;
    }

    public static function findOrCreateCreditToken(string $token): string
    {
        $transaction = Transaction::firstWhere('token', $token);

        return $transaction
            ? $transaction->creditToken
            : Transaction::createCreditToken($token, 0);
    }
}
