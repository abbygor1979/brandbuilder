<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('subscriptions.index');
    }

    public function store(Request $request)
    {
        $planId = $request->input('plan_id');
        $user = auth()->user();

        $user->newSubscription('default', $planId)->create($request->input('payment_method'));

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'Subscribed successfully!');
    }
}