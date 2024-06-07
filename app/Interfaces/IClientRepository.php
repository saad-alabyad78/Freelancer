<?php

namespace App\Interfaces;
use App\Models\Client;

interface IClientRepository extends IBaseRepository{
    public function update(Client $client , $data):Client;
}