<?php

namespace App\Repositories;
use App\Models\File;
use App\Models\Image;
use App\Models\Portfolio;
use App\Models\Skillable;
use App\Interfaces\IPortfolioRepository;

class PortfolioRepository extends BaseRepository implements IPortfolioRepository
{
    public function create($data):Portfolio 
    {
        $data['freelancer_id'] = auth('sanctum')->user()->role['id'] ;

        $portfolio = Portfolio::create($data);
            
            $skillables = array_map(function($item) use ($portfolio){
                return [
                    'skill_id' => $item ,
                    'skillable_id' => $portfolio->id ,
                    'skillable_type' => Portfolio::class ,
                ] ;
            } , $data['skill_ids']);
            
            Skillable::insert($skillables) ;

            if(array_key_exists('file_ids' , $data))
            {
                $idsArray = array_map( function($item){
                    return $item['id'];
                }, $data['file_ids']) ;
    
                File::whereIn('id' , $idsArray)
                     ->whereNull('filable_id')
                     ->whereNull('filable_type')
                     ->update([
                        'filable_id' => $portfolio->id ,
                        'filable_type' => Portfolio::class ,
                     ]) ;
            }
            
            if(array_key_exists('image_ids' , $data))
            {
                $idsArray = array_map( function($item){
                    return $item['id'];
                }, $data['image_ids']) ;

                Image::whereIn('id' , $idsArray)
                     ->whereNull('imagable_id')
                     ->whereNull('imagable_type')
                     ->update([
                        'imagable_id' => $portfolio->id ,
                        'imagable_type' => Portfolio::class ,
                     ]) ;
            }
            return $portfolio ;
    }
    public function update(Portfolio $portfolio , $data):Portfolio 
    {
        $portfolio->update($data) ;

        if(array_key_exists('skill_ids' , $data))
        {
            Skillable::where('skillable_id' , $portfolio->id)
                ->whereNotIn('skill_id' , $data['skill_ids'])
                ->delete() ;
                
            $skillables = array_map(function($id) use ($portfolio){
                return [
                    'skill_id' => $id , 
                    'skillable_id' => $portfolio->id ,
                    'skillable_type' => Portfolio::class ,
                ];
            } , $data['skill_ids']) ;

            Skillable::upsert($skillables , 'id') ;
                        
        }
        if(array_key_exists('file_ids' , $data))
        {
            File::where('filable_id' , $portfolio->id)
                ->whereNotIn('id' , $data['file_ids'])
                ->update([
                    'deleted' => true ,
                    'filable_id' => null ,
                    'fillable_type' => null ,
                    ]);

            File::where('filable_id' , $portfolio->id)
                ->whereIn('id' , $data['file_ids'])
                ->update([
                    'filable_id' => $portfolio->id ,
                    'filable_type' => Portfolio::class ,
                    ]);
        }
        if(array_key_exists('image_ids' , $data))
        {
            Image::where('imagable_id' , $portfolio->id)
                ->whereNotIn('id' , $data['image_ids'])
                ->update([
                    'deleted' => true ,
                    'imagable_id' => null ,
                    'imaglable_type' => null ,
                    ]);
                    
            Image::where('imagable_id' , $portfolio->id)
                ->whereIn('id' , $data['file_ids'])
                ->update([
                    'imagable_id' => $portfolio->id ,
                    'imagable_type' => Portfolio::class ,
                    ]);
        }

        return $portfolio ;
    }
}