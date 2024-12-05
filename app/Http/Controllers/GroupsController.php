<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Grouptype;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $groups;

    public function __construct()
    {
        $this->groups = new Groups();
    }
    public function index()
    {
        $title = 'Nhóm đối tượng';
        $groups = $this->groups->getAll();  
        $groupedGroups = $this->groups->getAllGroupedByType();
        return view('setup.groups.index', compact('title', 'groups', 'groupedGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grouptypes = Grouptype::all();
        $title = "Thêm mới nhóm đối tượng";
        return view('setup.groups.create', compact('title', 'grouptypes'));
    }
    public function dataObj(Request $request)
    {
        $data = $request->all();
        $dataGroup = $this->groups->dataObj($data['group_id']);
        return $dataGroup;
    }
    public function updateDataGroup(Request $request)
    {
        $data = $request->all();
        $dataGroup = $this->groups->updateDataGroup($data);
        return $dataGroup;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->groups->addGroup($request->all());
        if ($result == true) {
            $msg = redirect()->back()->with('warning', 'Nhóm đối tượng đã tồn tại');
        } else {
            $msg = redirect()->route('groups.index')->with('msg', 'Thêm mới nhóm đối tượng thành công');
        }
        return $msg;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $group = Groups::where('id', $id)
            ->where('workspace_id', Auth::user()->current_workspace)
            ->first();
        if ($group) {
            $title = $group->name;
        } else {
            abort('404');
            $title = '';
            return view('tables.groups.show', compact('title', 'group', 'workspacename'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $group = Groups::where('id', $id)
            ->where('workspace_id', Auth::user()->current_workspace)
            ->first();
        if ($group) {
            $title = $group->name;
        } else {
            abort('404');
            $title = '';
        }
        $getId = $id;
        $request->session()->put('idGr', $id);
        $grouptypes = Grouptype::all();
        $dataGroup = $this->groups->getDataGroup($id);
        return view('tables.groups.edit', compact('title', 'group', 'dataGroup', 'grouptypes', 'workspacename'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = session('idGr');
        $currentGroup = $this->groups->find($id);
        $data = [
            'group_code' => $request->group_code,
            'name' => $request->group_name_display,
            'description' => $request->group_desc,
            'workspace_id' => Auth::user()->current_workspace,
        ];
        if (!empty($request->grouptype_id)) {
            $data['grouptype_id'] = $request->grouptype_id;
        } else {
            $data['grouptype_id'] = $currentGroup->grouptype_id;
        }
        $this->groups->updateGroup($data, $id);
        session()->forget('idGr');
        return redirect(route('groups.index'))->with('msg', 'Sửa nhóm đối tượng thành công');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $workspace, string $id)
    {
        $group = Groups::find($id);
        if (!$group) {
            return back()->with('warning', 'Không tìm thấy loại đối tượng để xóa.');
        }
        // Mảng các điều kiện kiểm tra
        // $conditions = [
        //     1 => User::where('group_id', $id)->first(),
        //     2 => Guest::where('group_id', $id)->first(),
        //     3 => Provides::where('group_id', $id)->first(),
        //     4 => Products::where('group_id', $id)->first(),
        // ];
        // Kiểm tra nếu group type tồn tại và có bản ghi sử dụng group_id
        if (isset($conditions[$group->grouptype_id]) && $conditions[$group->grouptype_id]) {
            return back()->with('warning', 'Xóa thất bại do loại đối tượng vẫn đang còn sử dụng!');
        }
        $group->delete();
        return back()->with('msg', 'Xóa loại đối tượng thành công.');
    }
    //xóa đối tượng trong nhóm
    // public function deleteOJ(Request $request)
    // {
    //     $id = $request['id'];
    //     $idGroup = $request['idGroup'];
    //     $success = false;
    //     $typeGroup = Groups::where('id', $idGroup)->first();
    //     if ($typeGroup->grouptype_id == 1) {
    //         $user = User::where('id', $id)->first();
    //         if ($user) {
    //             $user->group_id = 0;
    //             $user->save();
    //             $success = true;
    //         } else {
    //             $success = false;
    //         }
    //     }
    //     if ($typeGroup->grouptype_id == 2) {
    //         $guest = Guest::where('id', $id)->first();
    //         if ($guest) {
    //             $guest->group_id = 0;
    //             $guest->save();
    //             $success = true;
    //         } else {
    //             $success = false;
    //         }
    //     }
    //     if ($typeGroup->grouptype_id == 3) {
    //         $provide = Provides::where('id', $id)->first();
    //         if ($provide) {
    //             $provide->group_id = 0;
    //             $provide->save();
    //             $success = true;
    //         } else {
    //             $success = false;
    //         }
    //     }
    //     if ($typeGroup->grouptype_id == 4) {
    //         $product = Products::where('id', $id)->first();
    //         if ($product) {
    //             $product->group_id = 0;
    //             $product->save();
    //             $success = true;
    //         } else {
    //             $success = false;
    //         }
    //     }
    //     if ($success) {
    //         $response = ['success' => true, 'msg' => 'Xóa đối tượng trong nhóm thành công!'];
    //     } else {
    //         $response = ['success' => false, 'msg' => 'Không tìm thấy đối tượng trong nhóm!'];
    //     }
    //     return response()->json($response);
    // }
}
