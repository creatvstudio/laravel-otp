<?php

namespace CreatvStudio\Otp;

use Illuminate\Database\Eloquent\Model;

class OtpSession extends Model
{
    protected $table = 'otp_sessions';

    protected $fillable = [
        'token'
    ];

    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function otpable()
    {
        return $this->morphTo('otpable');
    }
}
