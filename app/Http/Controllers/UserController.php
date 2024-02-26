<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.show_users', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function create()
    {
        $roles = Role::all();
        return view('users.Add_user', compact('roles'));

    }
/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'role_name' => 'required',
        ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $input['password'],
            'role_id' => $request->role_name,

        ]);
        $id = $request->role_name;
        $role = Role::find($id);
        $user->assignRole([$role->id]);

        return redirect()->route('users.index')
            ->with('success', 'تم اضافة المستخدم بنجاح');
    }

/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }
/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }
/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'role_name' => 'required',
        ]);
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
        $user = User::where('id', $id)->first();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $input['password'],
            'role_id' => $request->role_name,
        ]);
        $id = $request->role_name;
        $role = Role::find($id);
        $user->assignRole([$role->id]);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث معلومات المستخدم بنجاح');

    }
/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function destroy(Request $request)
    {
        User::find($request->user_id)->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}