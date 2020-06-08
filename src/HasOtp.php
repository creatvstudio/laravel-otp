<?php

namespace CreatvStudio\Otp;

use CreatvStudio\Otp\OtpSession;
use Illuminate\Support\Str;
use OTPHP\Factory;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;

trait HasOtp
{
    /**
     * The TOTP instance
     *
     * @var \OTPHP\TOTP
     */
    protected $otpInstance;

    /**
     * The column name of the "otp_uri".
     *
     * @var string
     */
    protected $otpUriName = 'otp_uri';

    /**
     * Get the column name for the "otp_uri"
     *
     * @return void
     */
    public function getOtpUriName()
    {
        return $this->otpUriName;
    }

    /**
     * Get the otp label
     *
     * @return void
     */
    public function getOtpLabel()
    {
        return $this->id;
    }

    public function generateOtpSecret()
    {
        return trim(Base32::encodeUpper(random_bytes(64)), '=');
    }

    public function getOtpUri()
    {
        return $this[$this->getOtpUriName()];
    }

    public function updateOtpUri()
    {
        $this->update([
            $this->otpUriName => $this->otp()->getProvisioningUri(),
        ]);
    }

    /**
     * Get an OTP code
     *
     * @return void
     */
    public function getOtpCode()
    {
        return $this->otp()->now();
    }

    /**
     * Get the OTP instance
     *
     * @return void
     */
    public function otp()
    {
        if (! $this->otpInstance) {
            $this->otpInstance = ($uri = $this->getOtpUri()) ? Factory::loadFromProvisioningUri($uri)
                : $this->generateOtpInstance();

            $this[$this->otpUriName] = $this->otp()->getProvisioningUri();
        }

        return $this->otpInstance;
    }

    /**
     * Otp attribute getter
     *
     * @return void
     */
    public function getOtpAttribute()
    {
        return $this->otp();
    }

    /**
     * Verify the OTP code
     *
     * @param string $otp
     * @param int|null $timestamp
     * @param int|null $window
     *
     * @return void
     */
    public function verifyOtp($otp, $timestamp = null, $window = null)
    {
        $window = $window ?: config('otp.window');

        return $this->otp()->verify($otp, $timestamp, $window);
    }

    /**
     * Get the otp sessions that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function otpSessions()
    {
        return $this->morphMany(OtpSession::class, 'otpable');
    }

    public function checkOtpSession($token)
    {
        return $this->otpSessions()->where('token', $token)->count() ? true : false;
    }

    public function getOtpQrCodeUri()
    {
        return $this->otp()->getQrCodeUri('https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . $this[$this->otpUriName], $this[$this->otpUriName]);
    }

    /**
     * Generate a new OTP instance
     *
     * @return \OTPHP\TOTP
     */
    protected function generateOtpInstance()
    {
        $otp = TOTP::create($this->generateOtpSecret(), config('otp.period'));
        $otp->setLabel($this->getOtpLabel());

        return $otp;
    }
}
