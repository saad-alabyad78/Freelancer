<?php

namespace App\Console\Commands;

use App\Models\Skill;
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
    protected $signature = 'seed:skills {--render}';

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
      if($this->option('render')) 
      {
        chunkSkillsUploadRender($this) ;
      }else{
        chunkSkillsInsertLocally($this) ;
      }
        
    }
}

function chunkSkillsUploadRender(SkillSeedCommand $object)
{
  $cnt = 0 ; 
  Skill::chunk(10000 , function($chunks) use ($object , &$cnt) {
    Http::timeout(240)->connectTimeout(120)->post('https://freelancer-l1w8.onrender.com/api/category/skill/chunk/insert' , $chunks) ;
    $object->info('uploade ' . ++$cnt * 10000 ) ;
  }); 
}

function chunkSkillsInsertLocally(SkillSeedCommand $object)
{
  $path = "/home/saad/Desktop/Freelancer/datasets/job_skills.csv";

  $generator = function($row){
      return processSkillsString($row[1]) ;
    };  
  $cnt = 0 ;
  $start = microtime(true) ;
  foreach(ChunkHelper::chunkFile($path , $generator , 2000) as $chunk){
      
    $object->info('$chunk ' . ++$cnt * 2000) ;
    
    $skills = array_merge(...$chunk) ;
    $skills = array_map(fn($item)=>['name' => $item] , $skills); 
    $skills = array_filter($skills , function($item){return $item['name'] ?? false;}) ;
  
    Skill::insertOrIgnore($skills) ;
  }
  $end = microtime(true) ;
  $object->info($end - $start) ;
}

function processSkillsString(string $skills)
{
  $skills = explode("," , $skills) ;
  return array_map(function($skill){
    $name = strtolower(trim($skill)) ;
    if (str($name)->length() < 40)
    return  $name;
    return null ;
  } , $skills) ;
}