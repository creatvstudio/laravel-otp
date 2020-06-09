<?php

// You can place your custom package configuration in here.
return [
    'session' => \CreatvStudio\Otp\OtpSession::class,

    /**
     * By default the number of digits is 6.
     * You can decide to use more (or less) digits.
     * More than 10 may be difficult to use by the owner.
     */
    'digits' => 6,

    /**
     * By default, the period
     * for a TOTP is 30 seconds.
     */
    'period' => 30,

    /**
     * By default, the counter for a HOTP is 0.
     */
    'counter' => 0,

    /**
     * The veirification window
     */
    'window' => 600,

    /**
     * The digest algorithm
     *
     * supports: md5, sha1, sha256 and sha512
     */
    'algorithm' => 'sha1',
];
