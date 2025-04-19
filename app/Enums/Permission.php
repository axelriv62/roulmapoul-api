<?php

namespace App\Enums;

enum Permission: string
{
    case CREATE_RENTAL = 'rental.create';
    case UPDATE_RENTAL = 'rental.update';
    case READ_ALL_RENTAL = 'rental.all.read';
    case CREATE_WITHDRAWAL = 'withdrawal.create';
    case CREATE_HANDOVER = 'handover.create';
    case CREATE_AMENDMENT = 'amendment.create';
    case READ_CUSTOMER = 'customer.read';
    case READ_ALL_CUSTOMER = 'customer.all.read';
    case CREATE_AGENT = 'agent.create';
}
