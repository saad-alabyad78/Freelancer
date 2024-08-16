<?php

namespace App\Constants;
use App\Models\Client;
use App\Models\Freelancer;

class RatingConstants{
    const FREELANCER = 'freelancer' ;
    const CLIENT =  'client' ;
    const MAP_TO_MODEL = [
        self::FREELANCER => Freelancer::class ,
        self::CLIENT => Client::class ,
    ] ;
}