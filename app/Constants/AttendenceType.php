<?php

namespace App\Constants;

class AttendenceType{
    const FULL_TIME = 'full-time' ;
    const PART_TIME = 'part-time' ;
    const INTERN = 'intern' ;

    public static $types = [self::FULL_TIME , self::PART_TIME , self::INTERN] ;
}