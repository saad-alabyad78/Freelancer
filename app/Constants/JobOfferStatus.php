<?php

namespace App\Constants;

class JobOfferStatus{
    const PENDING = 'pending' ;
    const ACTIVE = 'active' ;
    const CLOUSED = 'cloused' ;
    public static $types = [self::PENDING , self::ACTIVE , self::CLOUSED] ;
}