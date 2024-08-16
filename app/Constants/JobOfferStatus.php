<?php

namespace App\Constants;

class JobOfferStatus{
    const PENDING = 'pending' ;
    const ACTIVE = 'active' ;
    const CLOUSED = 'cloused' ;
    const DONE = 'done' ;
    public static $types = [self::PENDING , self::ACTIVE , self::CLOUSED , self::DONE] ;
}