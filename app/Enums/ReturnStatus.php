<?php

namespace App\Enums;

enum ReturnStatus: string
{
    case Requested = 'requested';
    case Received = 'received';
    case Rejected = 'rejected';
}
