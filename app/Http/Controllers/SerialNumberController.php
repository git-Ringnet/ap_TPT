<?php

namespace App\Http\Controllers;

use App\Models\SerialNumber;
use Illuminate\Http\Request;

class SerialNumberController extends Controller
{
    public function checkSN(Request $request)
    {
        $productId = $request->input('product_id');
        $serial = $request->input('serial');

        // Kiểm tra trong bảng serial_numbers
        $exists = SerialNumber::where('product_id', $productId)
            ->where('serial_code', $serial)
            ->where("status", "!=", "5")
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
