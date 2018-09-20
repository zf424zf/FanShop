<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class EmailVerificationNotification extends Notification
{
    //队列异步发送
    use Queueable;


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // 通过laravel自带随机字符串方法生成16位随机字符串
        $token = Str::random(16);
        // 将token写入缓存，设置过期时间为30分钟
        \Cache::set('email_verification_' . $notifiable->email, $token, 30);

        // 生成url
        $url = route('email_verification.verify',['email'=>$notifiable->email,'token' => $token]);
        return (new MailMessage())
            ->greeting($notifiable->name.'您好:') // 设置邮件的欢迎词
            ->subject('恭喜您，距离注册'.config('app.name').'成功只差一步啦,请验证您的邮箱') // 设置邮件标题
            ->line('请点击下方链接验证您的邮箱') // 邮件内容添加一行文字
            ->action('验证',$url); // 添加按钮
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
