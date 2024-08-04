<?php

namespace App\Constants;

class ClientOfferStatus{
    const PENDING = 'pending' ;
    const ACTIVE = 'active' ;
    const CLOUSED = 'cloused' ;
    const IN_PROGRESS = 'in_progress' ;
    const DONE  = 'done' ;
    public static $types = [
        self::PENDING ,
        self::ACTIVE ,
        self::CLOUSED ,
        self::IN_PROGRESS ,
        self::DONE ,
        ] ;
}