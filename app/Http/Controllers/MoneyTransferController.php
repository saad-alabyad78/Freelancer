<?php

namespace App\Http\Controllers;

use App\Models\MoneyTransfer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMoneyTransferRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @group Money Transfer Management
 *
 * APIs to manage money transfers.
 */
class MoneyTransferController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MoneyTransfer::class, 'moneyTransfer');
    }

    /**
     * Transfer money to a user.
     *
     * @bodyParam user_id integer required The ID of the user to transfer money to.
     * @bodyParam amount decimal required The amount of money to be transferred.
     */
    public function store(StoreMoneyTransferRequest $request)
    {
        $transfer = MoneyTransfer::create([
            'admin_id' => Auth::id(),
            'user_id' => $request->user_id,
            'amount' => $request->amount,
        ]);

        return response()->json($transfer, 201);
    }
}
