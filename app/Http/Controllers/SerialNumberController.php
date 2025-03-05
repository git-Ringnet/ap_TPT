<?php

namespace App\Http\Controllers;

use App\Models\ProductExport;
use App\Models\ProductImport;
use App\Models\SerialNumber;
use App\Models\WarehouseTransferItem;
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
                $exists = ProductImport::where('sn_id', $sn->id)
                    ->where('import_id', '!=', $request->import_id)
                    ->exists();
            }
        }
        if ($request->nameModal == "XH") {
            // Kiểm tra trong bảng serial_numbers
            $exists = SerialNumber::where('serial_code', $serial)
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
                    $exists = SerialNumber::where('serial_code', $serial)
                        ->where('status', 1)
                        ->exists();
                }
            } else {
                $exists = false;
            }
        }
        if ($request->nameModal == "NH") {
            // Kiểm tra trong bảng serial_numbers
            $exists = SerialNumber::where('serial_code', $serial)
                ->exists();
        }
        if ($request->nameModal == "PCK") {
            if ($request->warehouse_id == 1) {
                $exists = SerialNumber::where('serial_code', $serial)
                    ->where('status', 1)
                    ->exists();
            }
            if ($request->warehouse_id == 2) {
                $serial_borrow = $request->input('serial_borrow');
                $existsSerial = SerialNumber::where('serial_code', $serial)
                    ->doesntExist();

                $existsSerialBorrow = SerialNumber::where('serial_code', $serial_borrow)
                    ->where('status', 5)
                    ->exists();

                return response()->json([
                    'status' => 'success',
                    'existsSerial' => $existsSerial,
                    'existsSerialBorrow' => $existsSerialBorrow
                ]);
            }
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
            $exists = SerialNumber::where('serial_code', $serialNumber)
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
        if ($request->nameModal == "PCK") {
            if ($request->warehouse == 1) {
                // Kiểm tra trong bảng serial_numbers
                $exists = SerialNumber::where('serial_code', $serialNumber)
                    ->where('status', 1)
                    ->exists();
                if (!$exists) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
                } else {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }
            } else if ($request->warehouse == 2) {
                // Kiểm tra trong bảng serial_numbers
                $exists = SerialNumber::where('serial_code', $serialNumber)
                    ->exists();
                if ($exists) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
                } else {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }
            }
        }
        if ($request->nameModal == "CPCK") {
            if ($request->warehouse == 1) {
                // Lấy ID của serial cần kiểm tra
                $serial = SerialNumber::where('serial_code', $serialNumber)
                    ->first();

                if (!$serial) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không tồn tại.']);
                }

                // Kiểm tra nếu serial có status = 1
                $existsInSerials = $serial->status == 1;

                // Kiểm tra nếu serial đã có trong WarehouseTransferItem
                $existsInTransfer = WarehouseTransferItem::where('serial_number_id', $serial->id)->exists();

                if (!$existsInSerials && !$existsInTransfer) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
                } else {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }
            } else if ($request->warehouse == 2) {
                // Lấy serial trong DB
                $serial = SerialNumber::where('serial_code', $serialNumber)
                    ->first();

                // Nếu serial không tồn tại trong DB → Hợp lệ
                if (!$serial) {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }

                // Kiểm tra nếu serial đã có trong WarehouseTransferItem và có transfer_id trùng với warehouseTransferId
                $existsInTransfer = WarehouseTransferItem::where('serial_number_id', $serial->id)
                    ->where('transfer_id', $request->warehouseTransferId) // Kiểm tra transfer_id trùng
                    ->exists();

                if ($existsInTransfer) {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }

                // Nếu serial tồn tại nhưng không thuộc warehouseTransferId → Không hợp lệ
                return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
            }
        }
    }

    public function checkSNImportBorrow(Request $request)
    {
        $serialNumber = $request->input('serial_number');
        if ($request->nameModal == "PCK") {
            if ($request->warehouse == 2) {
                // Kiểm tra trong bảng serial_numbers
                $exists = SerialNumber::where('serial_code', $serialNumber)
                    ->where('status', 5)
                    ->exists();
                if (!$exists) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
                } else {
                    return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
                }
            }
        }
        if ($request->nameModal == "CPCK") {
            if ($request->warehouse == 2) {
                // Lấy ID của serial cần kiểm tra
                $serial = SerialNumber::where('serial_code', $serialNumber)
                    ->first();

                if (!$serial) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không tồn tại.']);
                }

                // Kiểm tra nếu serial có status = 1
                $existsInSerials = $serial->status == 5;

                // Kiểm tra nếu serial đã có trong WarehouseTransferItem
                $existsInTransfer = WarehouseTransferItem::where('sn_id_borrow', $serial->id)->exists();

                if (!$existsInSerials && !$existsInTransfer) {
                    return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
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
                return response()->json(['status' => 'error', 'message' => 'Số serial hết bảo hành.']);
            } else {
                return response()->json(['status' => 'success', 'message' => 'Số serial còn bảo hành.']);
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


    public function checkSNReplace(Request $request)
    {
        $seriRecord = SerialNumber::where('serial_code', $request->serialNumber)
            ->where('product_id', $request->product_id)
            ->whereIn('status', [1, 5])->where('warehouse_id', 2)
            ->first();

        if ($seriRecord) {
            return response()->json(['status' => 'success', 'message' => 'Số serial hợp lệ.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Số serial không hợp lệ.']);
        }
    }

    public function checkSerials(Request $request)
    {
        $formType = $request->input('form_type');
        $serialData = $request->input('serials');
        $warranty = $request->input('warranty');

        // dd($request->all());

        $sericheck = SerialNumber::find($serialData);

        if ($sericheck) {
            $warranty = DB::table('warranty_lookup')->where('sn_id', $sericheck->id)->where('id', $warranty)->first();
            if ($warranty) {
                if ($formType == 1) {
                    if ($warranty->status == 0) {
                        return response()->json(['status' => 'success', 'message' => 'Số serial còn bảo hành.']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Số serial hết bảo hành.']);
                    }
                }
                if ($formType == 2) {
                    if ($warranty->status == 1) {
                        return response()->json(['status' => 'success', 'message' => 'Số serial hết bảo hành.']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Số serial còn bảo hành.']);
                    }
                }
                if ($formType == 3) {
                    if ($warranty->status == 2) {
                        return response()->json(['status' => 'success', 'message' => 'Số serial bảo hành dịch vụ.']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Số serial không bảo hành dịch vụ.']);
                    }
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Không tìm thấy thông tin bảo hành.']);
            }
        } else {
        }
        return false;
    }

    public function checkAllWarranty(Request $request)
    {
        $formType = $request->input('form_type');
        $serialData = $request->input('serials'); // Nhận mảng serials
        $warrantyId = $request->input('warranty');

        if (!is_array($serialData) || empty($serialData)) {
            return response()->json(['status' => 'error', 'message' => 'Danh sách serials không hợp lệ.']);
        }

        $results = [];
        foreach ($serialData as $serial) {
            $serialCheck = SerialNumber::where('serial', $serial)->first(); // Giả sử có cột serial

            if ($serialCheck) {
                $warranty = DB::table('warranty_lookup')
                    ->where('sn_id', $serialCheck->id)
                    ->where('id', $warrantyId)
                    ->first();

                if ($warranty) {
                    if ($formType == 1 && $warranty->status == 0) {
                        $results[] = ['serial' => $serial, 'status' => 'success', 'message' => 'Còn bảo hành'];
                    } elseif ($formType == 2 && $warranty->status == 1) {
                        $results[] = ['serial' => $serial, 'status' => 'success', 'message' => 'Hết bảo hành'];
                    } elseif ($formType == 3 && $warranty->status == 2) {
                        $results[] = ['serial' => $serial, 'status' => 'success', 'message' => 'Bảo hành dịch vụ'];
                    } else {
                        $results[] = ['serial' => $serial, 'status' => 'error', 'message' => 'Trạng thái không phù hợp'];
                    }
                } else {
                    $results[] = ['serial' => $serial, 'status' => 'error', 'message' => 'Không tìm thấy thông tin bảo hành'];
                }
            } else {
                $results[] = ['serial' => $serial, 'status' => 'error', 'message' => 'Serial không tồn tại'];
            }
        }

        return response()->json($results);
    }

    public function checkbrands(Request $request)
    {
        $serialData = $request->input('serials');
        $product_id = $request->input('product_id');

        if (is_array($serialData)) {
            $result = [];
            foreach ($serialData as $serial) {
                $sericheck = SerialNumber::where('serial_code', $serial)->first();

                if ($sericheck) {
                    $result[$serial] = ['status' => 'success', 'message' => 'Số serial nội bộ.'];
                } else {
                    $result[$serial] = ['status' => 'external', 'message' => 'Số serial bên ngoài.'];
                }
            }
            // dd($product_id);
            return response()->json($result);
        }
        // Kiểm tra với một serial duy nhất
        $sericheck = SerialNumber::where('serial_code', $serialData)->first();
        if ($sericheck) {
            if ($sericheck->status == 1 || $sericheck->status == 5) {
                return response()->json(['status' => 'error', 'message' => 'Serial tồn tại trong kho.']);
            } elseif ($sericheck->product_id == $product_id) {
                return response()->json(['status' => 'success', 'message' => 'Số serial nội bộ.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Serial không thuộc sản phẩm này.']);
            }
        } else {
            return response()->json(['status' => 'external', 'message' => 'Số serial bên ngoài.']);
        }
    }
}
