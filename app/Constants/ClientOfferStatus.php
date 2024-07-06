<?php

namespace App\Constants;

class ClientOfferStatus{
    const PENDING = 'pending' ;
    const AVTIVE = 'active' ;
    const CLOUSED = 'cloused' ;
    public static $types = [self::PENDING , self::AVTIVE , self::CLOUSED] ;
}