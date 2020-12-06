<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'uuid', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreditTokenAttribute()
    {
        // ToDo: use passcodes to encrypt UUID
        // For now, the unencrypted UUID acts as the token
        return $this->uuid;
    }

    public static function createCreditToken(string $uuid = null, int $amount = null): string
    {
        return self::query()->create([
            'user_id' => Auth::id(),
            'uuid' => $uuid ?? Str::uuid(),
            'amount' => $amount
        ])->creditToken;
    }

    public static function findOrCreateCreditToken(string $uuid): string
    {
        $transaction = Transaction::firstWhere('uuid', $uuid);

        return $transaction
            ? $transaction->creditToken
            : Transaction::createCreditToken($uuid, 0);

    public function getActiveAttribute(): bool
    {
        if ($this->user_id) {
            return $this->amount === null;
        }

        return true;
    }

    public function getInactiveAttribute(): bool
    {
        return !$this->active;
    }

    }
}
