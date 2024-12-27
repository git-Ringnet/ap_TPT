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
        $phieuNhap = Imports::count();
        $phieuXuat = Exports::count();
        $tonKho = SerialNumber::where('status', 1)->count();
        $toiHanBT = InventoryLookup::where('status', 1)->count();
        $hangNhap = ProductImport::count();
        $hangXuat = ProductExport::count();
        $hangTiepNhan = ReceivedProduct::count();
        $hangTraHang = SerialNumber::where('status', 4)->count();
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
        $phieuTiepNhan = Receiving::where('status', 1)->where('state', 0)->count();
        $phieuTraHang = ReturnForm::count();
        $phieuChuaXL = Receiving::where('state', 1)->count();
        $phieuQuaHan = Receiving::where('state', 2)->count();
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
            'phieuQuaHan'
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
