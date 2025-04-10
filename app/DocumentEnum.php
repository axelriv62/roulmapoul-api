<?php

namespace App;

enum DocumentEnum : string
{
    case bill = 'bill';
    case withdrawal = 'withdrawal';
    case handovers = 'handovers';
}
