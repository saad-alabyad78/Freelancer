<?php

namespace App\Constants;

class JobOfferStatus{
    const PENDING = 'pending' ;
    const AVTIVE = 'active' ;
    const CLOUSED = 'cloused' ;
    public static $types = [self::PENDING , self::AVTIVE , self::CLOUSED] ;
}