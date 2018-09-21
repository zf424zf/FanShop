<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->configInit();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function configInit()
    {
        //设置默认字符串长度
        \Schema::defaultStringLength(191);
        //设置carbon格式化语言为中文
        Carbon::setLocale('zh');
    }
}
