<?php

namespace App\Enums;

enum DocumentType: string
{
    case BILL = 'bill';
    case WITHDRAWAL = 'withdrawal';
    case HANDOVER = 'handover';
}
