<?php

namespace App\Http\Controllers;

use App\Models\ProductExport;
use App\Models\ProductImport;
use App\Models\SerialNumber;
use Illuminate\Http\Request;

class SerialNumberController extends Controller
{
    public function checkSN(Request $request)
    {
        $productId = $request->input('product_id');
        $serial = $request->input('serial');
        $sn = SerialNumber::where('serial_code', $serial)->first();
        if ($request->nameModal == "CNH") {
            if ($sn) {
                $exists = ProductImport::where('product_id', $productId)
                    ->where('sn_id', $sn->id)
                    ->where('import_id', '!=', $request->import_id)
                    ->exists();
            }
        }
        if ($request->nameModal == "XH") {
            // Kiểm tra trong bảng serial_numbers
            $exists = SerialNumber::where('product_id', $productId)
                ->where('serial_code', $serial)
                ->where('status', 1)
                ->exists();
        }
        if ($request->nameModal == "CXH") {
            if ($sn) {
                $exists = ProductExport::where('product_id', $productId)
                    ->where('sn_id', $sn->id)
                    ->where('export_id', $request->import_id)
                    ->exists();
                if (!$exists) {
                    $exists = SerialNumber::where('product_id', $productId)
                        ->where('serial_code', $serial)
                        ->where('status', 1)
                        ->exists();
                }
            } else {
                $exists = false;
            }
        }
        if ($request->nameModal == "NH") {
            // Kiểm tra trong bảng serial_numbers
            $exists = SerialNumber::where('product_id', $productId)
                ->where('serial_code', $serial)
                ->exists();
        }

        return response()->json(['exists' => $exists]);
    }
}
