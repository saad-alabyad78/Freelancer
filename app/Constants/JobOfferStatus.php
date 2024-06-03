<?php

namespace App\Constants;

class JobOfferStatus{
    const PENDING = 'pending' ;
    const AVTIVE = 'active' ;
    const CLOSED = 'closed' ;
    const DELETED = 'deleted' ;
    public static $types = [self::PENDING , self::AVTIVE , self::CLOSED , self::DELETED] ;
}