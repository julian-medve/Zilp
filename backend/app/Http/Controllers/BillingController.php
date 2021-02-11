<?php

namespace App\Http\Controllers;

use Log;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;

class BillingController extends Controller
{
    use Billable;

    public function getStripeSetupIntent(Request $request){
        return auth()->user()->createSetupIntent();
    }

    public function savePaymentMethods( Request $request ){
        $user = $request->user();
        $paymentMethodID = $request->input('payment_method');
    
        if( $user->stripe_id == null ){
            $user->createAsStripeCustomer();
        }
    
        $user->addPaymentMethod( $paymentMethodID );
        $user->updateDefaultPaymentMethod( $paymentMethodID );
        $user->charge($request->input("amount"), $paymentMethodID);
        
        self::createTransaction(auth()->user()->id, $request->input('amount'), 'Stripe balance charge.');

        return response()->json([
            'success' => true,
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
    
    /**
     * Returns the payment methods the user has saved
     * 
     * @param Request $request The request data from the user.
     */
    public function getPaymentMethods( Request $request ){
        $user = $request->user();

        $methods = array();

        if( $user->hasPaymentMethod() ){
            foreach( $user->paymentMethods() as $method ){
                array_push( $methods, [
                    'id' => $method->id,
                    'brand' => $method->card->brand,
                    'last_four' => $method->card->last4,
                    'exp_month' => $method->card->exp_month,
                    'exp_year' => $method->card->exp_year,
                ] );
            }
        }

        return response()->json( $methods );
    }

    /**
     * Removes a payment method for the current user.
     * 
     * @param Request $request The request data from the user.
     */
    public function removePaymentMethod( Request $request ){
        $user = $request->user();
        $paymentMethodID = $request->get('id');

        $paymentMethods = $user->paymentMethods();

        foreach( $paymentMethods as $method ){
            if( $method->id == $paymentMethodID ){
                $method->delete();
                break;
            }
        }
        
        return response()->json( null, 204 );
    }
}
