<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Notifications\MailVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class VerifyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $name = Auth::user()->name;
        $code = Auth::user()->code;
        $expire_at = Auth::user()->expire_at;
        $sendonce = Auth::user()->sendonce;
        $id = Auth::user()->id;
        if ($sendonce == false) {
            Notification::send($user, new MailVerify($name, $code, $expire_at));
            $user = User::findOrFail($id);
            $user->update([
                'sendonce' => true,
            ]);
            return view('auth.verify');
        } else {
            return view('auth.verify');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {

        $id = Auth::user()->id;
        $code = Auth::user()->code;
        $expire_at = Auth::user()->expire_at;
        $now = date('Y-m-d H:i:s');

        if (($code == $request->code) && ($now < $expire_at)) {
            $user = User::findOrFail($id);
            $user->update([
                'code' => null,
                'expire_at' => null,
            ]);
            session()->flash('became_active', 'تم تفعيل حسابك بنجاح!');

            $controller = new HomeController();
            $controller->callAction('index', []);
            return redirect('home');

        } elseif ($code == null && $expire_at == null) {
            session()->flash('active', 'حسابك مفعل بالفعل');

            $controller = new HomeController();
            $controller->callAction('index', []);
            return redirect('home');

        } else {
            session()->flash('error', 'تأكد ان الكود الذي أدخلتة صحيح وموعد تفعيلة لم ينتهي!');
            return redirect('/verify');

        }

    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $cr)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        $user = Auth::user();
        $id = Auth::user()->id;
        $name = Auth::user()->name;
        $expire_at = Auth::user()->expire_at;
        $now = date('Y-m-d H:i:s');

        if (($now < $expire_at)) {
            session()->flash('check', 'لقد تم ارسال الكوداليك مسبقا قم بفحص بريدك الالكتروني!');
            return redirect('verify');

        } elseif (($now > $expire_at)) {
            $user = User::where('id', $id)->first();
            $user->update([
                'code' => rand(1000, 9999),
                'expire_at' => now()->addMinute(15),
            ]);
            $result = DB::table('users')->where('id', $id)->first();
            $new_code = $result->code;
            $now_expire_at = $result->expire_at;
            Notification::send($user, new MailVerify($name, $new_code, $now_expire_at));
            session()->flash('success', 'لقد تم ارسال الكود اليك بنجاح!');
            return redirect('verify');
        } else {
            session()->flash('active', 'حسابك مفعل بالفعل!');
            $controller = new HomeController();
            $controller->callAction('index', []);
            return redirect('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $cr)
    {
        //
    }
}