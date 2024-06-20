<?php

namespace App\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;

trait ServiceInstanceTrait
{
    /**
     * サービスクラスのインスタンスを取得する
     *
     * @param string $serviceClass
     * @return mixed
     * @throws BindingResolutionException
     */
    protected function getServiceInstance(string $serviceClass): mixed
    {
        return app()->make($serviceClass);
    }
}
