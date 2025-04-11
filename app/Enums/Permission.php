<?php

namespace App\Enums;

enum Permission: string
{
    case CREATE_RENTAL = 'rental.create';
    case UPDATE_RENTAL = 'rental.update';
    case READ_RENTAL = 'rental.read';
    case CREATE_WITHDRAWAL = 'withdrawal.create';
    case CREATE_HANDOVER = 'handover.create';
    case CREATE_AMENDMENT = 'amendment.create';
    case CLIENT_READ = 'client.read';
}
