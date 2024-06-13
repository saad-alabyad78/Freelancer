<?php

namespace App\Helpers;

class ChunkHelper{
    public static function chunkFile(string $path , callable $generator , int $chunkSize = 1000)
    {
        $file = fopen($path , 'r') ;
        $data = [] ;

        for($i = 1 ; ($row = fgetcsv($file , null , ',' ))!==false ; $i++)
        {
            $data[] = $generator($row) ;

            if($i % $chunkSize == 0 )
            {
                yield $data ;
                $data = [] ;
            }
        }
  
        if(!empty($data))
        {
            yield $data ;
        }

        fclose($file) ;
    }
}