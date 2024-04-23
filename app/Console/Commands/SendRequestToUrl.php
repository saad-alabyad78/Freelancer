<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendRequestToUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'http:request {url} {query?}';

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
        $url = $this->argument('url') ;
        $query = $this->argument('query') ;

        $this->info($url) ;
        $this->info($query) ;
        
        Http::get($url , $query) ;
    }
}
