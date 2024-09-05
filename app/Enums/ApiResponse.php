<?php

namespace App\Enums;

enum ApiResponse: string
{
    CASE WRONG_CREDENTIALS = 'WRONG_CREDENTIALS';
    CASE AUTHINTICATED = 'AUTHINTICATED';
    CASE NOT_ACTIVE = 'NOT_ACTIVE';
}
