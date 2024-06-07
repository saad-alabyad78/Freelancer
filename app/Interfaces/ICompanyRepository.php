<?php

namespace App\Interfaces;
use App\Models\Company;

interface ICompanyRepository extends IBaseRepository{
    public function create($data):Company ;
    public function update(Company $company , $data):Company ;
}