<?php

namespace Modules\User\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Modules\User\src\Http\Requests\UserRequest;
use Modules\User\src\Repositories\UserRepository;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $pageTitle = 'Quản Lý Người Dùng';
        return view('user::lists', compact('pageTitle'));
    }

    public function data()
    {
        $users = $this->userRepository->getAllUsers();

        return DataTables::of($users)
            ->addColumn('edit', function ($user) {
                return '<a href="' . route('admin.users.edit', $user) . '" class="btn btn-warning">Sửa</a>';
            })
            ->addColumn('delete', function ($user) {
                return '<a href="' . route('admin.users.delete', $user) . '" class="btn btn-danger delete-action">Xoá</a>';
            })
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->format('d/m/Y H:i:s');
            })
            ->rawColumns(['edit', 'delete'])
            ->toJson();
    }

    public function create()
    {
        $pageTitle = 'Thêm Người Dùng';
        return view('user::add', compact('pageTitle'));
    }

    public function store(UserRequest $request)
    {
        $attributes = [
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id,
            'password' => Hash::make($request->password)
        ];

        $this->userRepository->create($attributes);

        return redirect()->route('admin.users.index')->with('msg', __('user::messages.success'));
    }

    public function edit($id)
    {
        $pageTitle = 'Cập Nhật Người Dùng';
        $user = $this->userRepository->find($id);
        if (!$user) {
            abort(404);
        }

        return view('user::edit', compact('pageTitle', 'user'));
    }

    public function update(UserRequest $request, $id)
    {
        $data = $request->except('_token', 'password');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $this->userRepository->update($id, $data);

        return redirect()->back()->with('msg', __('user::messages.update.success'));
    }

    public function delete($id)
    {
        $this->userRepository->delete($id);
        return redirect()->back()->with('msg', __('user::messages.delete.success'));
    }
}
