<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $customers;

    public function __construct()
    {
        $this->customers = new Customers();
    }
    public function index()
    {
        // if (Auth::check()) {
        //     $title = "Khách hàng";
        //     $customers = $this->customers->getAllGuest();
        //     $dataa = $this->customers->getAllGuest();
        //     $users = $this->customers->getUserInGuests();
        //     $count = $customers->where('group_id', 0)->count();
        //     $groups = Groups::where('grouptype_id', 2)->get();
        //     return view('setup.customers.index', compact('title', 'customers', 'groups', 'count', 'users', 'dataa'));
        // } else {
        //     return redirect()->back()->with('warning', 'Vui lòng đăng nhập!');
        // }
        $title = "Khách hàng";
        $customers = $this->customers->getAllGuest();
        $count = $customers->where('group_id', 0)->count();
        $groups = Groups::where('group_type_id', 1)->get();
        return view('setup.customers.index', compact('title', 'customers', 'groups', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Thêm mới khách hàng";
        $groups = Groups::where('group_type_id', 1)->get();
        return view('setup.customers.create', compact('title', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->customers->addGuest($request->all());
        if ($result == true) {
            $msg = redirect()->back()->with('msg', 'Khách hàng đã tồn tại');
        } else {
            $msg = redirect()->route('customers.index')->with('msg', 'Thêm mới khách hàng thành công');
        }
        return $msg;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = "Xem chi tiết khách hàng";
        $customer = Customers::where('customers.id', $id)
            ->leftJoin('groups', 'customers.group_id', 'groups.id')
            ->select('customers.*', 'groups.group_name')
            ->first();
        return view('setup.customers.show', compact(
            'customer',
            'title'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Chỉnh sửa khách hàng";
        $customer = Customers::where('customers.id', $id)
            ->leftJoin('groups', 'customers.group_id', 'groups.id')
            ->select('customers.*', 'groups.group_name')
            ->first();
        $groups = Groups::where('group_type_id', 1)->get();
        return view('setup.customers.edit', compact('customer', 'title', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'customer_code' => $request->customer_code,
            'customer_name' => $request->customer_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'tax_code' => $request->tax_code,
            'note' => $request->note,
            'group_id' => $request->grouptype_id,
            'updated_at' => now(),
        ];
        $this->customers->updateCustomer($data, $id);
        return redirect(route('customers.index'))->with('msg', 'Sửa khách hàng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customers::find($id);
        if (!$customer) {
            return back()->with('warning', 'Không tìm thấy khách hàng để xóa');
        }
        // $check = DetailExport::where('guest_id', $id)->first();
        // if ($check) {
        //     return back()->with('warning', 'Xóa thất bại do khách hàng này đang báo giá!');
        // }
        $customer->delete();
        return back()->with('msg', 'Xóa khách hàng thành công');
    }
}
