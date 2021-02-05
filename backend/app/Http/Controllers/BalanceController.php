<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BalanceController extends Controller
{
    public function getBillingInfo(): JsonResponse
    {
        $transactions = Transaction::select([
            'id',
            'transaction_uid AS transactionUid',
            'amount',
            'description',
            'CREATED_AT AS createdAt'
        ])
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'balance' => auth()->user()->balance,
            'fullName' => ucfirst(auth()->user()->first_name) . ' ' . ucfirst(auth()->user()->last_name),
            'transactions' => $transactions
        ]);
    }

    public function getStripePublicKey(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'pk' => env('STRIPE_KEY')
        ]);
    }

    public static function createTransaction($user_id, $amount, $description): void {
        $user = User::where('id', $user_id)->first();
        $user->balance += $amount;
        $user->save();

        $dt = Carbon::now();

        $user_transactions_count = count(Transaction::where('user_id', $user_id)->get());

        $new_transaction = new Transaction;
        $new_transaction->transaction_uid = $user_id . $dt->year . str_pad($dt->month, 2, '0', STR_PAD_LEFT) . str_pad($dt->day, 2, '0', STR_PAD_LEFT) . ($user_transactions_count + 1);
        $new_transaction->user_id = $user_id;
        $new_transaction->amount = $amount;
        $new_transaction->description = $description;
        $new_transaction->save();
    }

    public function stripeCharge(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'paymentId' => 'required'
        ]);

        try {
            auth()->user()->charge($request->input('amount') * 100, $request->input('paymentId'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }

        self::createTransaction(auth()->user()->id, $request->input('amount'), 'Stripe balance charge.');

        return response()->json([
            'success' => true
        ]);
    }

    public function transferFunds(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'password' => 'required',
            'toUserId' => 'required|numeric'
        ]);

        $user = User::where('id', auth()->user()->id)->first();

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'error' => 'wrong_password'
            ]);
        }

        if (auth()->user()->balance < $request->input('amount')) {
            return response()->json([
                'success' => false,
                'error' => 'not_enough_funds'
            ]);
        }

        $destination_user = User::where('id', $request->input('toUserId'))->first();

        self::createTransaction($user->id, -$request->input('amount'), 'Transfer to user ' . $destination_user->first_name . ' ' . $destination_user->last_name . ' - id: ' . $destination_user->id);
        self::createTransaction($destination_user->id, $request->input('amount'), 'Transferred from user ' . $user->first_name . ' ' . $user->last_name . ' - id: ' . $user->id);

        return response()->json([
            'success' => true
        ]);
    }
}
