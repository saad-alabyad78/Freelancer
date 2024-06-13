<?php

namespace App\Console\Commands;

use App\Helpers\ChunkHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SkillSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:skills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = "/home/saad/Desktop/Freelancer/datasets/job_skills.csv";

        $generator = function($row){
            return processSkillsString($row[1]) ;
          };  
        $cnt = 0 ;
        $start = microtime(true) ;
        foreach(ChunkHelper::chunkFile($path , $generator , 1700) as $chunk){
            
          $this->info('$chunk ' . ++$cnt * 1700) ;
          
          $skills = array_merge(...$chunk) ;
          $skills = array_map(fn($item)=>['name' => $item] , $skills);
          

            Http::withHeader('Accept' , 'application/json')
          ->post('http://127.0.0.1:8000/api/category/skill/chunk/insert' ,
            $skills) ;

            $this->info('done') ;
        }
        $end = microtime(true) ;
        $this->info($end - $start) ;
    }
}

function processSkillsString(string $skills)
{
  $skills = explode("," , $skills) ;
  return array_map(function($skill){
    return  strtolower(trim($skill));
  } , $skills) ;
}