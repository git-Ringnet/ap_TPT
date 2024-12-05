<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Providers;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    private $providers;
    public function __construct()
    {
        $this->providers = new Providers();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Nhà cung cấp";
        $dataa = $this->providers->getAllProvide();

        $groups = Groups::where('group_type_id', 2)->get();

        $providers = Providers::where('group_id', 0)->get();
        return view('setup.providers.index', compact('title', 'providers', 'dataa', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Thêm mới nhà cung cấp";
        $category = Groups::where('group_type_id', 2)->get();
        return view('setup.providers.create', compact('title', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->providers->addProvide($request->all());
        if ($result['status'] == true) {
            $msg = redirect()->back()->with('warning', 'Mã số thuế hoặc tên hiển thị đã tồn tại');
        } else {
            $msg = redirect()->route('providers.index')->with('msg', 'Thêm mới nhà cung cấp thành công');
        }
        return $msg;
    }

    /**
     * Display the specified resource.
     */
    public function show(Providers $providers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $provider = Providers::findOrFail($id);
        $title = "Chỉnh sửa nhà cung cấp";
        $category = Groups::where('group_type_id', 2)->get();

        return view('setup.providers.edit', compact('title', 'provider', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->providers->updateProvide($request->all(), $id);
        if ($status) {
            return redirect(route('providers.index'))->with('warning', 'Mã số thuế đã tồn tại');
        } else {
            return redirect(route('providers.index'))->with('msg', 'Sửa nhà cung cấp thành công');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $provider = Providers::find($id);
        if (!$provider) {
            return back()->with('warning', 'Không tìm thấy nhà cung cấp để xóa');
        }
        // $check = DetailExport::where('guest_id', $id)->first();
        // if ($check) {
        //     return back()->with('warning', 'Xóa thất bại do khách hàng này đang báo giá!');
        // }
        $provider->delete();
        return back()->with('msg', 'Xóa nhà cung cấp thành công');
    }
}
