<?php

namespace App\Services;
use DOMDocument;
use SimpleXMLElement;
use SebastianBergmann\Type\VoidType;
use App\Exceptions\FileNotFonudException;
use App\Exceptions\FileCouldNotReadException;

class xmlService
{
    public $xmlContent ;

    public function __construct(string $path)
    {
        $this->xmlContent = static::read($path) ;
    }

    public static function read(string $path)
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

    public function sortJobRoles(string $path = 'dynamics/job_roles.xml')
    {
        $xs = new xmlService($path) ;

        $job_roles = [] ;

        foreach($xs->xmlContent->job_role as $role)
        {
            $role_name = $role['name'] ;
            $skills = [] ;
            foreach($role->skills->skill as $skill)
            {
                $skills[] = $skill ;
            }
            sort($skills) ;

            $job_roles[] = ['name' => $role_name , 'skills' => $skills] ;
        }
        usort($job_roles , function($role1 , $role2){
            return strcmp($role1['name'] , $role2['name']); 
        });

        $sortedXML = new SimpleXMLElement('<job_roles></job_roles>') ;

        foreach($job_roles as $role)
        {
            $roleElement = $sortedXML->addChild('job_role') ;
            $roleElement->addAttribute('name' , $role['name']) ;
            $skillsElement = $roleElement->addChild('skills') ;

            foreach($role['skills'] as $skill)
            {
                $skillsElement->addChild('skill' , $skill) ;
            }
        }
        $xs::putXML($path , $sortedXML) ;

    }

    public function sortSkills(string $path = 'dynamics/skills.xml') : void
    {
        $xs = new xmlService($path) ;

        $skills = [] ;

        foreach($xs->xmlContent->skill as $skill)
        {
            $skills[] = (string)$skill ;
        }
        
        sort($skills) ;

        $sortedSkills = new SimpleXMLElement('<skills></skills>') ;

        foreach($skills as $skill)
        {
            $sortedSkills->addChild('skill' , $skill) ;
        }

        $this->putXML($path , $sortedSkills) ;
    }

    public static function toJson(SimpleXMLElement $xml) 
    {
        return json_decode(json_encode($xml)) ;
    }

    public static function putXML(string $path , SimpleXMLElement $xml)
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Save the formatted XML to a string
        $xml = $dom->saveXML();

        file_put_contents(database_path($path) , $xml) ;
    }
}