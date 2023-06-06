<?php

namespace App\Model;

class CloudflareStatusCodes
{
    public static array $statusCodes = [
        520 => [
            'description' => 'Web Server Returned an Unknown Error',
            'no_standard' => true,
        ],
        521 => [
            'description' => 'Web Server Is Down',
            'no_standard' => true,
        ],
        522 => [
            'description' => 'Connection Timed out',
            'no_standard' => true,
        ],
        523 => [
            'description' => 'Origin Is Unreachable',
            'no_standard' => true,
        ],
        524 => [
            'description' => 'A Timeout Occurred',
            'no_standard' => true,
        ],
        525 => [
            'description' => 'SSL Handshake Failed',
            'no_standard' => true,
        ],
        526 => [
            'description' => 'Invalid SSL Certificate',
            'no_standard' => true,
        ],
        527 => [
            'description' => 'Railgun Error',
            'no_standard' => true,
        ],
        530 => [
            'description' => 'Origin DNS Error',
            'no_standard' => true,
        ],
    ];
}
