<?php

namespace App\Services;
use SimpleXMLElement;
use App\Exceptions\FileNotFonudException;
use App\Exceptions\FileCouldNotReadException;

class xmlService
{
    public $xmlContent ;

    public function __construct(string $path)
    {
        $this->xmlContent = $this->read($path) ;
    }

    public function read(string $path)
    {
        
        $file_path = database_path($path) ;

        if(!file_exists($file_path)){
            throw new FileNotFonudException() ;
        }

        $content = file_get_contents($file_path) ;

        if($content === false){
            throw new FileCouldNotReadException() ;
        }

        return simplexml_load_string($content) ;
    }

    public static function toJson(SimpleXMLElement $xml)
    {
        return json_decode(json_encode($xml)) ;
    }
}