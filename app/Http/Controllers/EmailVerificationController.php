<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * 邮箱验证控制器
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function verify(Request $request)
    {
        //从url取出token和email
        $email = $request->input('email');
        $token = $request->input('token');

        //如果任意一个为空，则说明是违法链接
        if (!$email || !$token) {
            throw new InvalidRequestException('验证链接错误');
        }

        //从缓存中读取数据，将从url获取到的token与缓存中的对比，如果缓存不存在或者数据不一致，则抛出异常
        if ($token != \Cache::get('email_verification_' . $email)) {
            throw new InvalidRequestException('验证链接不存在或者已过期');
        }

        //根据邮箱取出数据库里的用户
        if (!$user = User::where('email', $email)->first()) {
            throw new InvalidRequestException('用户不存在');
        }

        // 删除email缓存。
        \Cache::forget('email_verification_' . $email);
        // 更新'email_verified` 字段为 `true`。
        $user->update(['email_verified' => true]);

        // 告知用户邮箱验证成功。
        return view('pages.success', ['msg' => '邮箱验证成功']);
    }


    /**
     * 手动发送验证邮件
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws InvalidRequestException
     */
    public function send(Request $request)
    {
        $user = $request->user();
        //判断用户是否激活邮箱
        if ($user->email_verified) {
            throw new InvalidRequestException('你已经验证过邮箱了');
        }

        //调用notify方法发送邮箱激活通知
        $user->notify(new EmailVerificationNotification());
        return view('pages.success', ['msg' => '邮件已成功发送，请至您的注册邮箱进行验证']);
    }
}
