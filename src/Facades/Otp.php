<?php

namespace CreatvStudio\Otp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CreatvStudio\Otp\Skeleton\SkeletonClass
 */
class Otp extends Facade
{
    public static function routes(array $options = [])
    {
        static::$app->make('router')->otp($options);
    }

    public static function send()
    {
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'otp';
    }
}
