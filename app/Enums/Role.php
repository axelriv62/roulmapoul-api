<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case AGENT = 'agent';
}
