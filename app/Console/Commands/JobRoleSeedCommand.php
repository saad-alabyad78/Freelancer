<?php

namespace App\Console\Commands;

use Exception;
use App\Models\JobRole;
use App\Helpers\ChunkHelper;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class JobRoleSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:job-roles {--render}';

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
        chunkJobRolesUploadRender($this) ;
      }else{
        chunkJobRolesInsertLocaly($this) ;
      }
        
      $this->info('bye bye') ;
    }
}

function chunkJobRolesUploadRender(JobRoleSeedCommand $object)
{
  $cnt = 0 ; 
  JobRole::chunk(10000 , function($chunks) use ($object , &$cnt) {
    Http::timeout(240)->connectTimeout(120)->post('https://freelancer-l1w8.onrender.com/api/category/job_role/chunk/insert' , $chunks) ;
    $object->info('uploade' . ++$cnt * 10000 ) ;
  }); 
}
function chunkJobRolesInsertLocaly(JobRoleSeedCommand $object)
{
  $path = "/home/saad/Desktop/Freelancer/datasets/job_skills.csv";

        $generator = function($row){
            return [
              'name' => processJobString($row[0]) ,
            ];
          };  

        $start = microtime(true) ;

        $cnt = 0;
       
            foreach (ChunkHelper::chunkFile($path, $generator, 10000) as $chunk) {
              
              $object->info('Processing chunk: ' . ++$cnt * 10000);
                
                $chunk = array_filter($chunk, function($item) {
                    return $item['name'] !== null && $item['name'] !== "";
                });

               JobRole::insertOrIgnore($chunk) ;

              $object->info('Done') ;
                      
            }


        $end = microtime(true) ;
        $object->info($end - $start) ;
}
function processJobString($input) {

  $input = strtolower($input);

  // Remove everything before the word "view/"
  $prefixes = ["view/" , '/'];
  foreach($prefixes as $p)
  {
    if (strpos($input, $p) !== false) {
      $input = substr($input, strpos($input, $p) + strlen($p) );
      break ;
    }
  }
  
  $suffixes = ["-at" , "-pt" , "-i" , "-va" ,'1','2','3','4','5','6','7','8','9','0' , 'senior' , 'teach' , 'lead'] ;
  foreach($suffixes as $s)
  {
    // Remove anything after "at"
    if (strpos($input, $s) !== false) {
        $input = substr($input, 0, strpos($input, $s ));
    }
  }

  // Replace "-" with space
  $input = str_replace("-", " ", $input);

  // Remove prefix and suffix spaces
  $input = trim($input);

  // Convert every letter to lower case

  return $input==""? null : $input;
}



