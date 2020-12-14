<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['url'];

    /**
     * Number of seconds for a play to become payable
     *
     * @var int
     */
    public static int $minPlayTime = 60;

    /**
     * Payment (qp) per valid video play
     *
     * @var int
     */
    public static int $paymentAmount = 25;
}
