<?php

namespace App\Console\Commands;

use Exception;
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
    protected $signature = 'seed:job-roles';

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
            return [
              'name' => processJobString($row[0]) ,
            ];
          };  

        $start = microtime(true) ;

        $cnt = 0;
       
            foreach (ChunkHelper::chunkFile($path, $generator, 20000) as $chunk) {
              
              $this->info('Processing chunk: ' . ++$cnt * 20000);
                
                $chunk = array_filter($chunk, function($item) {
                    return $item['name'] !== null && $item['name'] !== "";
                });

                Http::withHeader('Accept', 'application/json')
                ->post('http://127.0.0.1:8000/api/category/job_role/chunk/insert', $chunk);

              $this->info('Done') ;
                      
            }


        $end = microtime(true) ;
        $this->info($end - $start) ;
        
    }
}

function processJobString($input) {
    // Remove everything before the word "view/"
    $prefixes = ["view/" , '/'];
    foreach($prefixes as $p)
    {
      if (strpos($input, $p) !== false) {
        $input = substr($input, strpos($input, $p) + strlen($p) );
        break ;
      }
    }
    
    $suffixes = ["-at" , "-pt" , "-i" , "-va" ,'1','2','3','4','5','6','7','8','9','0'] ;
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
    $input = strtolower($input);

    return $input==""? null : $input;
}



