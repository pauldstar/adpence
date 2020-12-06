<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Transaction;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    public function __invoke(PaymentRequest $request)
    {
        $transaction = Transaction::firstWhere('uuid', $request->get('token'));

        if (is_null($transaction) || $transaction->inactive) {
            return response()->json(['success' => false], 401);
        }

        $fulfilled = $transaction->fulfill($request->get('amount'));

        return Response::json(['success' => $fulfilled], $fulfilled ? 200: 401);
    }
}
