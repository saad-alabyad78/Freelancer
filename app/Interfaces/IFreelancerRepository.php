<?php

namespace App\Interfaces;
use App\Models\Freelancer;

Interface IFreelancerRepository extends IBaseRepository
{
    public function create($data):Freelancer;
    public function update(Freelancer $freelancer , $data):Freelancer;
}