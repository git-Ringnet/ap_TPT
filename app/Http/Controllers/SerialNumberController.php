<?php

namespace App\Http\Controllers;

use App\Models\ProductExport;
use App\Models\ProductImport;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function checkSNImport(Request $request)
    {
        $serialNumber = $request->input('serial_number');
        // dd($request->all());
        // Kiểm tra số serial trong cơ sở dữ liệu
        if ($request->nameModal == "NH") {
            $exists = DB::table('serial_numbers')->where('serial_code', $serialNumber)->exists();
            if ($exists) {
                return response()->json(['status' => 'error', 'message' => 'Số serial đã tồn tại.']);
            } else {
                return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
            }
        }
        if ($request->nameModal == "XH") {
            // Kiểm tra trong bảng serial_numbers
            $exists = SerialNumber::where('product_id', $request->product_id)
                ->where('serial_code', $serialNumber)
                ->where('status', 1)
                ->exists();
            if (!$exists) {
                return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
            } else {
                return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
            }
        }
        if ($request->nameModal == "TN") {
            if ($request->branch_id == 1) {
                $seri = $this->getSerial($serialNumber);
                if (!$seri) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không tồn tại.']);
                }
                // Logic đặc biệt cho form_type = 3
                if ($request->form_type == 3) {
                    $seriWithStatus4Or5 = $this->getSerialWithStatus($serialNumber, [4, 5]);
                    if (!$seriWithStatus4Or5) {
                        return response()->json(['status' => 'error', 'message' => 'Số serial không nằm trong diện bảo hành dịch vụ.']);
                    }
                    $seri = $seriWithStatus4Or5;
                }
                $warranty = $this->getWarranty($seri->id, $request->product_id);
                if (!$warranty) {
                    return response()->json(['status' => 'error', 'message' => 'Không tìm thấy thông tin bảo hành.']);
                }

                return $this->evaluateWarrantyByFormType($warranty->status, $request->form_type);
            }
            if ($request->branch_id == 2) {
                $seri = $this->getSerial($serialNumber);
                if ($seri) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial thuộc trong kho, không phải hàng bên ngoài.']);
                } else {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }
            }
        }
    }
    private function getSerialWarranty($serialCode)
    {
        return SerialNumber::with('warrantyLookups')->where('serial_code', $serialCode)->first();
    }
    private function getSerial($serialCode)
    {
        return DB::table('serial_numbers')->where('serial_code', $serialCode)->first();
    }

    public function getSerialWithStatus($serialNumber, $statuses)
    {
        return SerialNumber::where('serial_code', $serialNumber)
            ->whereIn('status', (array) $statuses) // Chấp nhận một giá trị hoặc mảng
            ->first();
    }

    private function getWarranty($serialId, $productId = null)
    {
        $query = DB::table('warranty_lookup')->where('sn_id', $serialId);
        if ($productId) {
            $query->where('product_id', $productId);
        }
        return $query->first();
    }
    private function evaluateWarrantyByFormType($status, $formType)
    {
        if ($formType == 1) { // Loại phiếu bảo hành
            if ($status == 0) {
                return response()->json(['status' => 'success', 'message' => 'Số serial còn bảo hành.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Số serial hết bảo hành.']);
            }
        }

        if ($formType == 2) { // Loại phiếu dịch vụ
            if ($status == 1) {
                return response()->json(['status' => 'success', 'message' => 'Số serial hết bảo hành.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Số serial còn bảo hành.']);
            }
        }

        if ($formType == 3) { // Loại phiếu bảo hành dịch vụ
            if ($status == 1) {
                return response()->json(['status' => 'success', 'message' => 'Số serial hết bảo hành.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Số serial còn bảo hành.']);
            }
        }
    }

    public function checkSerial(Request $request)
    {
        $branchId = $request->branch_id;
        $formType = $request->form_type;
        $serialData = $request->serial_data;
        $results = [];

        foreach ($serialData as $data) {
            $serialNumber = $data['serial'];
            $productId = $data['productId'];
            $valid = false; // Mặc định là không hợp lệ
            $message = '';

            if ($branchId == 1) {
                $seri = $this->getSerial($serialNumber);
                if (!$seri) {
                    $message = 'Số serial không tồn tại.';
                } elseif ($formType == 3) {
                    $seriWithStatus4Or5 = $this->getSerialWithStatus($serialNumber, [4, 5]);
                    if (!$seriWithStatus4Or5) {
                        $message = 'Số serial không nằm trong diện bảo hành dịch vụ.';
                    } else {
                        $seri = $seriWithStatus4Or5;
                        $valid = true;
                    }
                } else {
                    $warranty = $this->getWarranty($seri->id, $productId);

                    if (!in_array($formType, [1, 2])) {
                        $message = 'Loại biểu mẫu không hợp lệ.';
                    } elseif (!$warranty) {
                        $message = 'Không tìm thấy thông tin bảo hành.';
                    } else {
                        $isValidType1 = ($formType == 1 && $warranty->status == 0);
                        $isValidType2 = ($formType == 2 && $warranty->status == 1);
                        if ($isValidType1 || $isValidType2) {
                            $valid = true;
                        } else {
                            $message = 'Sản phẩm còn bảo hành.';
                        }
                    }
                }
            } elseif ($branchId == 2) {
                $seri = $this->getSerial($serialNumber);
                if ($seri) {
                    $message = 'Số serial thuộc trong kho, không phải hàng bên ngoài.';
                } else {
                    $valid = true;
                }
            }

            $results[] = [
                'serial' => $serialNumber,
                'valid' => $valid,
                'message' => $message,
            ];
        }

        return response()->json(['status' => 'success', 'serials' => $results]);
    }

    public function checkSerialNumbers(Request $request)
    {
        $productId = $request->input('productId');
        $serialNumbers = $request->input('serialNumbers', []);

        // Tách các serial numbers hợp lệ và không hợp lệ
        $validSerials = [];
        $invalidSerials = [];

        foreach ($serialNumbers as $serial) {
            $exists = SerialNumber::where('product_id', $productId)
                ->where('serial_code', $serial)
                ->where('status', 1)
                ->exists();

            if ($exists) {
                $validSerials[] = $serial;
            } else {
                $invalidSerials[] = $serial;
            }
        }

        return response()->json([
            'exists' => $validSerials,
            'invalid' => $invalidSerials,
        ]);
    }
}
