<?php

namespace App\Constants;

class FreelancerOfferStatus{
    const PENDING = 'pending' ;
    const ACTIVE = 'active' ;
    const CLOUSED = 'cloused' ;
    public static $types = [self::PENDING , self::ACTIVE , self::CLOUSED] ;
}