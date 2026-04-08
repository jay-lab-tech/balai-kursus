<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\PaymentController as BasePaymentController;
use Illuminate\Http\Request;

class PembayaranController extends BasePaymentController
{
    public function handleMidtransNotification(Request $request)
    {
        return $this->notification($request);
    }

    /**
     * Store - OLD METHOD (Deprecated)
     * Kept for reference only - manual payment system removed
     */
    public function store(Request $request, $id)
    {
        return back()->with('error', 'Manual payment tidak lagi tersedia. Gunakan metode pembayaran online via Midtrans.');
    }
}
