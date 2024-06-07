<?php

namespace App\Interfaces;
use App\Models\Portfolio;

interface IPortfolioRepository extends IBaseRepository
{
    public function create($data):Portfolio ;
    public function update(Portfolio $portfolio , $data):Portfolio ;
}