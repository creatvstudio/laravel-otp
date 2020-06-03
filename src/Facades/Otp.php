<?php

namespace CreatvStudio\Otp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CreatvStudio\Otp\Skeleton\SkeletonClass
 */
class Otp extends Facade
{
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
