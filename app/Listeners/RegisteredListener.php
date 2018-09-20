<?php

namespace App\Listeners;

use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisteredListener implements ShouldQueue
{
    // 异步监听事件
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    // 当事件被触发时，对应该事件的监听器的 handle() 方法就会被调用
    public function handle(Registered $event)
    {
        //获取刚注册的用户
        $user = $event->user;
        // 调用notify通知
        $user->notify(new EmailVerificationNotification());
    }
}
