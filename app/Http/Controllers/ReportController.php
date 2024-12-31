<?php

namespace App\Http\Controllers;

use App\Models\Exports;
use App\Models\Imports;
use App\Models\InventoryLookup;
use App\Models\Product;
use App\Models\ProductExport;
use App\Models\ProductImport;
use App\Models\Quotation;
use App\Models\ReceivedProduct;
use App\Models\Receiving;
use App\Models\Report;
use App\Models\ReturnForm;
use App\Models\SerialNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function reportOverview()
    {
        $title = 'Tổng quát báo cáo';
        // Báo cáo phiếu xuất nhập
        $phieuNhap = Imports::count();
        $phieuXuat = Exports::count();
        $fromDateExIm = collect([Imports::min('date_create'), Exports::min('date_create')])->filter()->min();
        $toDateExIm = collect([Imports::max('date_create'), Exports::max('date_create')])->filter()->max();
        //Báo cáo tồn kho
        $tonKho = SerialNumber::where('status', 1)->count();
        $toiHanBT = InventoryLookup::where('status', 1)->count();
        $fromDateInventory = collect([
            SerialNumber::where('status', 1)->min('created_at'),
            InventoryLookup::where('status', 1)->min('created_at')
        ])->filter()->min();
        $toDateInventory = collect([
            SerialNumber::where('status', 1)->max('created_at'),
            InventoryLookup::where('status', 1)->max('created_at')
        ])->filter()->max();
        //Báo cáo hàng xuất nhập
        $hangNhap = ProductImport::count();
        $hangXuat = ProductExport::count();
        $fromDateProductExIm = collect([ProductImport::min('created_at'), ProductExport::min('created_at')])->filter()->min();
        $toDateProductExIm = collect([ProductImport::max('created_at'), ProductExport::max('created_at')])->filter()->max();
        //Bao cao hang tiep nhan va tra hang
        $hangTiepNhan = ReceivedProduct::count();
        $hangTraHang = SerialNumber::where('status', 4)->count();
        $fromDateReceiveReturn = collect([ReceivedProduct::min('created_at'), SerialNumber::where('status', 4)->min('created_at')])->filter()->min();
        $toDateReceiveReturn = collect([ReceivedProduct::max('created_at'), SerialNumber::where('status', 4)->max('created_at')])->filter()->max();
        //Báo cáo phiếu báo giá
        $phieuHoanThanh = ReturnForm::where('status', 1)->count();
        $phieuKhongDongY = ReturnForm::where('status', 2)->count();
        $tongTienHoanThanh = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
            ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
            ->where('return_form.status', 1)
            ->sum('quotations.total_amount');
        $tongTienKhongDongY = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
            ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
            ->where('return_form.status', 2)
            ->sum('quotations.total_amount');
        $dates = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
            ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
            ->selectRaw('MIN(quotations.created_at) as min_date, MAX(quotations.created_at) as max_date')
            ->first();
        $fromDateQuotation = $dates->min_date;
        $toDateQuotation = $dates->max_date;

        //Báo cáo phiếu tiếp nhận và trả hàng
        $phieuTiepNhan = Receiving::count();
        $phieuTraHang = ReturnForm::count();
        $phieuChuaXL = Receiving::where('state', 1)->count();
        $phieuQuaHan = Receiving::where('state', 2)->count();
        $fromDatePhieuTNTH = collect([
            Receiving::min('created_at'),
            ReturnForm::min('created_at'),
            Receiving::where('state', 1)->min('created_at'),
            Receiving::where('state', 2)->min('created_at')
        ])->filter()->min();
        $toDatePhieuTNTH = collect([
            Receiving::max('created_at'),
            ReturnForm::max('created_at'),
            Receiving::where('state', 1)->max('created_at'),
            Receiving::where('state', 2)->max('created_at')
        ])->filter()->max();
        return view('reports.overview', compact(
            'title',
            'phieuNhap',
            'phieuXuat',
            'tonKho',
            'toiHanBT',
            'hangNhap',
            'hangXuat',
            'hangTiepNhan',
            'hangTraHang',
            'phieuHoanThanh',
            'phieuKhongDongY',
            'tongTienHoanThanh',
            'tongTienKhongDongY',
            'phieuTiepNhan',
            'phieuTraHang',
            'phieuChuaXL',
            'phieuQuaHan',
            'fromDateExIm',
            'toDateExIm',
            'fromDateInventory',
            'toDateInventory',
            'fromDateProductExIm',
            'toDateProductExIm',
            'fromDateReceiveReturn',
            'toDateReceiveReturn',
            'fromDateQuotation',
            'toDateQuotation',
            'fromDatePhieuTNTH',
            'toDatePhieuTNTH'
        ));
    }

    public function reportExportImport()
    {
        $title = 'Báo cáo hàng xuất nhập';
        $products = Product::with(['imports', 'exports'])
            ->get()
            ->map(function ($product) {
                return [
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'total_import' => $product->imports->sum('quantity'),
                    'total_export' => $product->exports->sum('quantity'),
                ];
            });
        return view('reports.export_import', compact('title', 'products'));
    }

    public function reportReceiptReturn()
    {
        $title = 'Báo cáo hàng xuất nhập';
        $products = Product::with(['receivedProducts', 'returnProducts'])
            ->get()
            ->map(function ($product) {
                return [
                    'product_code' => $product->product_code,
                    'product_name' => $product->product_name,
                    'total_receive' => $product->receivedProducts->sum('quantity'),
                    'total_return' => $product->returnProducts->sum('quantity'),
                ];
            });
        return view('reports.receipt_return', compact('title', 'products'));
    }

    public function reportQuotation()
    {
        $title = 'Báo cáo hàng xuất nhập';
        $quotations = Quotation::join('receiving', 'receiving.id', 'quotations.reception_id')
            ->join('return_form', 'return_form.reception_id', 'receiving.id')
            ->select('quotations.*', 'return_form.status')
            ->get();
        return view('reports.quotation', compact('title', 'quotations'));
    }

    public function filterReportOverview(Request $request)
    {
        $dataValue = $request->dataValue;
        $dataName = $request->dataName;
        $response = [];
        if ($dataName == 'phieuXN') {
            if ($dataValue == 0) {
                $phieuNhap = Imports::count();
                $phieuXuat = Exports::count();
                $fromDateExIm = collect([Imports::min('date_create'), Exports::min('date_create')])->filter()->min();
                $toDateExIm = collect([Imports::max('date_create'), Exports::max('date_create')])->filter()->max();
                $response = [
                    'phieuNhap' => $phieuNhap,
                    'phieuXuat' => $phieuXuat,
                    'ngayBatDau' => $fromDateExIm,
                    'ngayKetThuc' => $toDateExIm
                ];
            }
            if ($dataValue == 1) {
                //Lấy dữ liệu của tháng này
                $phieuNhap = Imports::whereMonth('date_create', date('m'))->whereYear('date_create', date('Y'))->count();
                $phieuXuat = Exports::whereMonth('date_create', date('m'))->whereYear('date_create', date('Y'))->count();
                $fromDateExIm = date('Y-m-01');
                $toDateExIm = date('Y-m-t');
                $response = [
                    'phieuNhap' => $phieuNhap,
                    'phieuXuat' => $phieuXuat,
                    'ngayBatDau' => $fromDateExIm,
                    'ngayKetThuc' => $toDateExIm
                ];
            }
            if ($dataValue == 2) {
                //lấy dữ liệu của tháng trước
                $phieuNhap = Imports::whereMonth('date_create', date('m', strtotime('-1 month')))->whereYear('date_create', date('Y', strtotime('-1 month')))->count();
                $phieuXuat = Exports::whereMonth('date_create', date('m', strtotime('-1 month')))->whereYear('date_create', date('Y', strtotime('-1 month')))->count();
                $fromDateExIm = date('Y-m-01', strtotime('first day of last month'));
                $toDateExIm = date('Y-m-t', strtotime('last day of last month'));
                $response = [
                    'phieuNhap' => $phieuNhap,
                    'phieuXuat' => $phieuXuat,
                    'ngayBatDau' => $fromDateExIm,
                    'ngayKetThuc' => $toDateExIm
                ];
            }
            if ($dataValue == 3) {
                //lấy dữ liệu của 3 tháng trước
                $phieuNhap = Imports::whereMonth('date_create', date('m', strtotime('-3 month')))->whereYear('date_create', date('Y', strtotime('-3 month')))->count();
                $phieuXuat = Exports::whereMonth('date_create', date('m', strtotime('-3 month')))->whereYear('date_create', date('Y', strtotime('-3 month')))->count();
                $fromDateExIm = date('Y-m-01', strtotime('first day of -3 months'));
                $toDateExIm = date('Y-m-t', strtotime('last day of -1 months'));
                $response = [
                    'phieuNhap' => $phieuNhap,
                    'phieuXuat' => $phieuXuat,
                    'ngayBatDau' => $fromDateExIm,
                    'ngayKetThuc' => $toDateExIm
                ];
            }
        }
        if ($dataName == 'phieuTNTH') {
            if ($dataValue == 0) {
                $phieuTiepNhan = Receiving::count();
                $phieuTraHang = ReturnForm::count();
                $phieuChuaXL = Receiving::where('state', 1)->count();
                $phieuQuaHan = Receiving::where('state', 2)->count();
                $fromDatePhieuTNTH = collect([
                    Receiving::min('created_at'),
                    ReturnForm::min('created_at'),
                    Receiving::where('state', 1)->min('created_at'),
                    Receiving::where('state', 2)->min('created_at')
                ])->filter()->min();
                $toDatePhieuTNTH = collect([
                    Receiving::max('created_at'),
                    ReturnForm::max('created_at'),
                    Receiving::where('state', 1)->max('created_at'),
                    Receiving::where('state', 2)->max('created_at')
                ])->filter()->max();
                $response = [
                    'phieuTiepNhan' => $phieuTiepNhan,
                    'phieuTraHang' => $phieuTraHang,
                    'phieuChuaXL' => $phieuChuaXL,
                    'phieuQuaHan' => $phieuQuaHan,
                    'ngayBatDau' => $fromDatePhieuTNTH,
                    'ngayKetThuc' => $toDatePhieuTNTH
                ];
            }
            if ($dataValue == 1) {
                //Lấy dữ liệu của tháng này
                $phieuTiepNhan = Receiving::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $phieuTraHang = ReturnForm::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $phieuChuaXL = Receiving::where('state', 1)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $phieuQuaHan = Receiving::where('state', 2)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $fromDatePhieuTNTH = date('Y-m-01');
                $toDatePhieuTNTH = date('Y-m-t');
                $response = [
                    'phieuTiepNhan' => $phieuTiepNhan,
                    'phieuTraHang' => $phieuTraHang,
                    'phieuChuaXL' => $phieuChuaXL,
                    'phieuQuaHan' => $phieuQuaHan,
                    'ngayBatDau' => $fromDatePhieuTNTH,
                    'ngayKetThuc' => $toDatePhieuTNTH
                ];
            }
            if ($dataValue == 2) {
                //Lấy dữ liệu của tháng trước
                $phieuTiepNhan = Receiving::whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $phieuTraHang = ReturnForm::whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $phieuChuaXL = Receiving::where('state', 1)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $phieuQuaHan = Receiving::where('state', 2)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $fromDatePhieuTNTH = date('Y-m-01', strtotime('first day of last month'));
                $toDatePhieuTNTH = date('Y-m-t', strtotime('last day of last month'));
                $response = [
                    'phieuTiepNhan' => $phieuTiepNhan,
                    'phieuTraHang' => $phieuTraHang,
                    'phieuChuaXL' => $phieuChuaXL,
                    'phieuQuaHan' => $phieuQuaHan,
                    'ngayBatDau' => $fromDatePhieuTNTH,
                    'ngayKetThuc' => $toDatePhieuTNTH
                ];
            }
            if ($dataValue == 3) {
                //lấy dữ liệu của 3 tháng trước
                $phieuTiepNhan = Receiving::whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $phieuTraHang = ReturnForm::whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $phieuChuaXL = Receiving::where('state', 1)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $phieuQuaHan = Receiving::where('state', 2)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $fromDatePhieuTNTH = date('Y-m-01', strtotime('first day of -3 months'));
                $toDatePhieuTNTH = date('Y-m-t', strtotime('last day of -1 months'));
                $response = [
                    'phieuTiepNhan' => $phieuTiepNhan,
                    'phieuTraHang' => $phieuTraHang,
                    'phieuChuaXL' => $phieuChuaXL,
                    'phieuQuaHan' => $phieuQuaHan,
                    'ngayBatDau' => $fromDatePhieuTNTH,
                    'ngayKetThuc' => $toDatePhieuTNTH
                ];
            }
        }
        if ($dataName == 'baoCaoTK') {
            if ($dataValue == 0) {
                $tonKho = SerialNumber::where('status', 1)->count();
                $toiHanBT = InventoryLookup::where('status', 1)->count();
                $fromDateInventory = collect([
                    SerialNumber::where('status', 1)->min('created_at'),
                    InventoryLookup::where('status', 1)->min('created_at')
                ])->filter()->min();
                $toDateInventory = collect([
                    SerialNumber::where('status', 1)->max('created_at'),
                    InventoryLookup::where('status', 1)->max('created_at')
                ])->filter()->max();
                $response = [
                    'tonKho' => $tonKho,
                    'toiHanBT' => $toiHanBT,
                    'ngayBatDau' => $fromDateInventory,
                    'ngayKetThuc' => $toDateInventory
                ];
            }
            if ($dataValue == 1) {
                //Lấy dữ liệu của tháng này
                $tonKho = SerialNumber::where('status', 1)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $toiHanBT = InventoryLookup::where('status', 1)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $fromDateInventory = date('Y-m-01');
                $toDateInventory = date('Y-m-t');
                $response = [
                    'tonKho' => $tonKho,
                    'toiHanBT' => $toiHanBT,
                    'ngayBatDau' => $fromDateInventory,
                    'ngayKetThuc' => $toDateInventory
                ];
            }
            if ($dataValue == 2) {
                //Lấy dữ liệu của tháng trước
                $tonKho = SerialNumber::where('status', 1)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $toiHanBT = InventoryLookup::where('status', 1)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $fromDateInventory = date('Y-m-01', strtotime('first day of last month'));
                $toDateInventory = date('Y-m-t', strtotime('last day of last month'));
                $response = [
                    'tonKho' => $tonKho,
                    'toiHanBT' => $toiHanBT,
                    'ngayBatDau' => $fromDateInventory,
                    'ngayKetThuc' => $toDateInventory
                ];
            }
            if ($dataValue == 3) {
                //lấy dữ liệu của 3 tháng trước
                $tonKho = SerialNumber::where('status', 1)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $toiHanBT = InventoryLookup::where('status', 1)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $fromDateInventory = date('Y-m-01', strtotime('first day of -3 months'));
                $toDateInventory = date('Y-m-t', strtotime('last day of -1 months'));
                $response = [
                    'tonKho' => $tonKho,
                    'toiHanBT' => $toiHanBT,
                    'ngayBatDau' => $fromDateInventory,
                    'ngayKetThuc' => $toDateInventory
                ];
            }
        }
        if ($dataName == 'hangXN') {
            if ($dataValue == 0) {
                $hangNhap = ProductImport::count();
                $hangXuat = ProductExport::count();
                $fromDateProductExIm = collect([ProductImport::min('created_at'), ProductExport::min('created_at')])->filter()->min();
                $toDateProductExIm = collect([ProductImport::max('created_at'), ProductExport::max('created_at')])->filter()->max();
                $response = [
                    'hangNhap' => $hangNhap,
                    'hangXuat' => $hangXuat,
                    'ngayBatDau' => $fromDateProductExIm,
                    'ngayKetThuc' => $toDateProductExIm
                ];
            }
            if ($dataValue == 1) {
                //Lấy dữ liệu của tháng này
                $hangNhap = ProductImport::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $hangXuat = ProductExport::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $fromDateProductExIm = date('Y-m-01');
                $toDateProductExIm = date('Y-m-t');
                $response = [
                    'hangNhap' => $hangNhap,
                    'hangXuat' => $hangXuat,
                    'ngayBatDau' => $fromDateProductExIm,
                    'ngayKetThuc' => $toDateProductExIm
                ];
            }
            if ($dataValue == 2) {
                //Lấy dữ liệu của tháng trước
                $hangNhap = ProductImport::whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $hangXuat = ProductExport::whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $fromDateProductExIm = date('Y-m-01', strtotime('first day of last month'));
                $toDateProductExIm = date('Y-m-t', strtotime('last day of last month'));
                $response = [
                    'hangNhap' => $hangNhap,
                    'hangXuat' => $hangXuat,
                    'ngayBatDau' => $fromDateProductExIm,
                    'ngayKetThuc' => $toDateProductExIm
                ];
            }
            if ($dataValue == 3) {
                //lấy dữ liệu của 3 tháng trước
                $hangNhap = ProductImport::whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $hangXuat = ProductExport::whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $fromDateProductExIm = date('Y-m-01', strtotime('first day of -3 months'));
                $toDateProductExIm = date('Y-m-t', strtotime('last day of -1 months'));
                $response = [
                    'hangNhap' => $hangNhap,
                    'hangXuat' => $hangXuat,
                    'ngayBatDau' => $fromDateProductExIm,
                    'ngayKetThuc' => $toDateProductExIm
                ];
            }
        }
        if ($dataName == "hangTNTH") {
            if ($dataValue == 0) {
                $hangTiepNhan = ReceivedProduct::count();
                $hangTraHang = SerialNumber::where('status', 4)->count();
                $fromDateReceiveReturn = collect([ReceivedProduct::min('created_at'), SerialNumber::where('status', 4)->min('created_at')])->filter()->min();
                $toDateReceiveReturn = collect([ReceivedProduct::max('created_at'), SerialNumber::where('status', 4)->max('created_at')])->filter()->max();
                $response = [
                    'hangTiepNhan' => $hangTiepNhan,
                    'hangTra' => $hangTraHang,
                    'ngayBatDau' => $fromDateReceiveReturn,
                    'ngayKetThuc' => $toDateReceiveReturn
                ];
            }
            if ($dataValue == 1) {
                //Lấy dữ liệu của tháng này
                $hangTiepNhan = ReceivedProduct::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $hangTraHang = SerialNumber::where('status', 4)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $fromDateReceiveReturn = date('Y-m-01');
                $toDateReceiveReturn = date('Y-m-t');
                $response = [
                    'hangTiepNhan' => $hangTiepNhan,
                    'hangTra' => $hangTraHang,
                    'ngayBatDau' => $fromDateReceiveReturn,
                    'ngayKetThuc' => $toDateReceiveReturn
                ];
            }
            if ($dataValue == 2) {
                //Lấy dữ liệu của tháng trước
                $hangTiepNhan = ReceivedProduct::whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $hangTraHang = SerialNumber::where('status', 4)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $fromDateReceiveReturn = date('Y-m-01', strtotime('first day of last month'));
                $toDateReceiveReturn = date('Y-m-t', strtotime('last day of last month'));
                $response = [
                    'hangTiepNhan' => $hangTiepNhan,
                    'hangTra' => $hangTraHang,
                    'ngayBatDau' => $fromDateReceiveReturn,
                    'ngayKetThuc' => $toDateReceiveReturn
                ];
            }
            if ($dataValue == 3) {
                //lấy dữ liệu của 3 tháng trước
                $hangTiepNhan = ReceivedProduct::whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $hangTraHang = SerialNumber::where('status', 4)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $fromDateReceiveReturn = date('Y-m-01', strtotime('first day of -3 months'));
                $toDateReceiveReturn = date('Y-m-t', strtotime('last day of -1 months'));
                $response = [
                    'hangTiepNhan' => $hangTiepNhan,
                    'hangTra' => $hangTraHang,
                    'ngayBatDau' => $fromDateReceiveReturn,
                    'ngayKetThuc' => $toDateReceiveReturn
                ];
            }
        }
        if ($dataName == "phieuBG") {
            if ($dataValue == 0) {
                $phieuHoanThanh = ReturnForm::where('status', 1)->count();
                $phieuKhongDongY = ReturnForm::where('status', 2)->count();
                $tongTienHoanThanh = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 1)
                    ->sum('quotations.total_amount');
                $tongTienKhongDongY = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 2)
                    ->sum('quotations.total_amount');
                $dates = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->selectRaw('MIN(quotations.created_at) as min_date, MAX(quotations.created_at) as max_date')
                    ->first();
                $fromDateQuotation = $dates->min_date;
                $toDateQuotation = $dates->max_date;
                $response = [
                    'phieuHoanThanh' => $phieuHoanThanh,
                    'phieuKhongDongY' => $phieuKhongDongY,
                    'tongTienHoanThanh' => $tongTienHoanThanh,
                    'tongTienKhongDongY' => $tongTienKhongDongY,
                    'ngayBatDau' => $fromDateQuotation,
                    'ngayKetThuc' => $toDateQuotation
                ];
            }
            if ($dataValue == 1) {
                //Lấy dữ liệu của tháng này
                $phieuHoanThanh = ReturnForm::where('status', 1)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $phieuKhongDongY = ReturnForm::where('status', 2)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                $tongTienHoanThanh = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 1)
                    ->whereMonth('quotations.created_at', date('m'))
                    ->whereYear('quotations.created_at', date('Y'))
                    ->sum('quotations.total_amount');
                $tongTienKhongDongY = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 2)
                    ->whereMonth('quotations.created_at', date('m'))
                    ->whereYear('quotations.created_at', date('Y'))
                    ->sum('quotations.total_amount');
                $dates = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->whereMonth('quotations.created_at', date('m'))
                    ->whereYear('quotations.created_at', date('Y'))
                    ->selectRaw('MIN(quotations.created_at) as min_date, MAX(quotations.created_at) as max_date')
                    ->first();
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-t');
                $response = [
                    'phieuHoanThanh' => $phieuHoanThanh,
                    'phieuKhongDongY' => $phieuKhongDongY,
                    'tongTienHoanThanh' => $tongTienHoanThanh,
                    'tongTienKhongDongY' => $tongTienKhongDongY,
                    'ngayBatDau' => $fromDate,
                    'ngayKetThuc' => $toDate
                ];
            }
            if ($dataValue == 2) {
                //Lấy dữ liệu của tháng trước
                $phieuHoanThanh = ReturnForm::where('status', 1)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $phieuKhongDongY = ReturnForm::where('status', 2)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
                $tongTienHoanThanh = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 1)
                    ->whereMonth('quotations.created_at', date('m', strtotime('-1 month')))
                    ->whereYear('quotations.created_at', date('Y', strtotime('-1 month')))
                    ->sum('quotations.total_amount');
                $tongTienKhongDongY = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 2)
                    ->whereMonth('quotations.created_at', date('m', strtotime('-1 month')))
                    ->whereYear('quotations.created_at', date('Y', strtotime('-1 month')))
                    ->sum('quotations.total_amount');
                $fromDate = date('Y-m-01', strtotime('first day of last month'));
                $toDate = date('Y-m-t', strtotime('last day of last month'));
                $response = [
                    'phieuHoanThanh' => $phieuHoanThanh,
                    'phieuKhongDongY' => $phieuKhongDongY,
                    'tongTienHoanThanh' => $tongTienHoanThanh,
                    'tongTienKhongDongY' => $tongTienKhongDongY,
                    'ngayBatDau' => $fromDate,
                    'ngayKetThuc' => $toDate
                ];
            }
            if ($dataValue == 3) {
                //lấy dữ liệu của 3 tháng trước
                $phieuHoanThanh = ReturnForm::where('status', 1)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $phieuKhongDongY = ReturnForm::where('status', 2)->whereMonth('created_at', date('m', strtotime('-3 month')))->whereYear('created_at', date('Y', strtotime('-3 month')))->count();
                $tongTienHoanThanh = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 1)
                    ->whereMonth('quotations.created_at', date('m', strtotime('-3 month')))
                    ->whereYear('quotations.created_at', date('Y', strtotime('-3 month')))
                    ->sum('quotations.total_amount');
                $tongTienKhongDongY = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                    ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                    ->where('return_form.status', 2)
                    ->whereMonth('quotations.created_at', date('m', strtotime('-3 month')))
                    ->whereYear('quotations.created_at', date('Y', strtotime('-3 month')))
                    ->sum('quotations.total_amount');
                $fromDate = date('Y-m-01', strtotime('first day of -3 months'));
                $toDate = date('Y-m-t', strtotime('last day of -1 months'));
                $response = [
                    'phieuHoanThanh' => $phieuHoanThanh,
                    'phieuKhongDongY' => $phieuKhongDongY,
                    'tongTienHoanThanh' => $tongTienHoanThanh,
                    'tongTienKhongDongY' => $tongTienKhongDongY,
                    'ngayBatDau' => $fromDate,
                    'ngayKetThuc' => $toDate
                ];
            }
        }
        return response()->json([
            'details' => $response
        ]);
    }

    public function filterReportPeriodTime(Request $request)
    {
        $date_start = $request->date_start ? $request->date_start . ' 00:00:00' : null;
        $date_end = $request->date_end ? $request->date_end . ' 23:59:59' : null;
        $dataName = $request->dataName;
        $response = [];
        if ($dataName == 'phieuXN') {
            $phieuNhap = Imports::whereBetween('date_create', [$date_start, $date_end])->count();
            $phieuXuat = Exports::whereBetween('date_create', [$date_start, $date_end])->count();
            $response = [
                'phieuNhap' => $phieuNhap,
                'phieuXuat' => $phieuXuat,
                'ngayBatDau' => $date_start,
                'ngayKetThuc' => $date_end
            ];
        }
        if ($dataName == "phieuTNTH") {
            $phieuTiepNhan = Receiving::whereBetween('created_at', [$date_start, $date_end])->count();
            $phieuTraHang = ReturnForm::whereBetween('created_at', [$date_start, $date_end])->count();
            $phieuChuaXL = Receiving::where('state', 1)->whereBetween('created_at', [$date_start, $date_end])->count();
            $phieuQuaHan = Receiving::where('state', 2)->whereBetween('created_at', [$date_start, $date_end])->count();
            $response = [
                'phieuTiepNhan' => $phieuTiepNhan,
                'phieuTraHang' => $phieuTraHang,
                'phieuChuaXL' => $phieuChuaXL,
                'phieuQuaHan' => $phieuQuaHan,
                'ngayBatDau' => $date_start,
                'ngayKetThuc' => $date_end
            ];
        }
        if ($dataName == 'baoCaoTK') {
            $tonKho = SerialNumber::where('status', 1)->whereBetween('created_at', [$date_start, $date_end])->count();
            $toiHanBT = InventoryLookup::where('status', 1)->whereBetween('created_at', [$date_start, $date_end])->count();
            $response = [
                'tonKho' => $tonKho,
                'toiHanBT' => $toiHanBT,
                'ngayBatDau' => $date_start,
                'ngayKetThuc' => $date_end
            ];
        }
        if ($dataName == 'hangXN') {
            $hangNhap = ProductImport::whereBetween('created_at', [$date_start, $date_end])->count();
            $hangXuat = ProductExport::whereBetween('created_at', [$date_start, $date_end])->count();
            $response = [
                'hangNhap' => $hangNhap,
                'hangXuat' => $hangXuat,
                'ngayBatDau' => $date_start,
                'ngayKetThuc' => $date_end
            ];
        }
        if ($dataName == "hangTNTH") {
            $hangTiepNhan = ReceivedProduct::whereBetween('created_at', [$date_start, $date_end])->count();
            $hangTraHang = SerialNumber::where('status', 4)->whereBetween('created_at', [$date_start, $date_end])->count();
            $response = [
                'hangTiepNhan' => $hangTiepNhan,
                'hangTra' => $hangTraHang,
                'ngayBatDau' => $date_start,
                'ngayKetThuc' => $date_end
            ];
        }
        if($dataName == "phieuBG") {
            $phieuHoanThanh = ReturnForm::where('status', 1)->whereBetween('created_at', [$date_start, $date_end])->count();
            $phieuKhongDongY = ReturnForm::where('status', 2)->whereBetween('created_at', [$date_start, $date_end])->count();
            $tongTienHoanThanh = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                ->where('return_form.status', 1)
                ->whereBetween('return_form.created_at', [$date_start, $date_end])
                ->sum('quotations.total_amount');
            $tongTienKhongDongY = ReturnForm::join('receiving', 'return_form.reception_id', '=', 'receiving.id')
                ->join('quotations', 'receiving.id', '=', 'quotations.reception_id')
                ->where('return_form.status', 2)
                ->whereBetween('return_form.created_at', [$date_start, $date_end])
                ->sum('quotations.total_amount');
            $response = [
                'phieuHoanThanh' => $phieuHoanThanh,
                'phieuKhongDongY' => $phieuKhongDongY,
                'tongTienHoanThanh' => $tongTienHoanThanh,
                'tongTienKhongDongY' => $tongTienKhongDongY,
                'ngayBatDau' => $date_start,
                'ngayKetThuc' => $date_end
            ];
        }
        return response()->json([
            'details' => $response
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
