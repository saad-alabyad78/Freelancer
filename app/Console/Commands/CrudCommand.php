<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CrudCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy:crud {model}{controller}{s_request}{u_request}{resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create crud controller ") ';



    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $controller = $this->argument('controller') ?? ($model . 'Controller') ;
        $s_request = $this->argument('s_request') ?? ('Store' . ($model) . 'Request') ;
        $u_request = $this->argument('u_request') ?? ('Update' .($model) . 'Request') ;
        $resource = $this->argument('resource') ?? ($model .'Resource') ;

        $modelPath = app_path("Models/{$model}.php");
        if(!File::exists($modelPath)){
            $this->info('the model ' . $model . ' does not exists! ') ;
            return ;
        }

        $controllerPath = app_path("Http/Controllers/{$controller}.php");

        $this->info($controllerPath) ;

        if(File::exists($controllerPath)){
            $this->error('the controller ' . $controller . ' already exists! ') ;
            return ;
        }

        $controllerContent =
        $this->ControllerContent($model , $controller , $u_request , $s_request , $resource) ;

        Artisan::call('make:controller ' . $controller);
        File::put($controllerPath , $controllerContent) ;

        Artisan::call('make:request ' . $s_request);
        Artisan::call('make:request ' . $u_request);
        Artisan::call('make:resource ' . $resource);
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'model' => 'What is the model name ? ',
        ];
    }

    private function ControllerContent($Model , $controller , $updateR , $storeR , $resource) : string
    {
        $model = strtolower($Model) ;

        return
         "<?php\n"
        ."\n"
        ."namespace App\Http\Controllers;\n"
        ."\n"
        ."use App\Models\\{$Model};\n"
        ."use App\Http\Requests\\{$updateR};\n"
        ."use App\Http\Requests\\{$storeR};\n"
        ."use App\Http\Resources\\{$resource};\n"
        ."\n"
        ."class {$controller} extends Controller\n"
        ."{\n"
        ."\n"
        ."    //CRUD\n"
        ."    public function index()\n"
        ."    {\n"
        ."        return {$resource}::collection({$Model}::paginate());\n"
        ."    }\n"
        ."\n"
        ."    public function store({$storeR} \$request)\n"
        ."    {\n"
        ."        \${$model} = {$Model}::create(\$request->validated());\n"
        ."\n"
        ."        return new {$resource}(\${$model});\n"
        ."    }\n"
        ."\n"
        ."    public function show({$Model} \${$model} )\n"
        ."    {\n"
        ."        return new {$resource}(\${$model});\n"
        ."    }\n"
        ."\n"
        ."    public function update({$updateR} \$request, {$Model} \${$model})\n"
        ."    {\n"
        ."        \${$model}->update(\$request->validated());\n"
        ."\n"
        ."        return new {$resource}(\${$model}) ;\n"
        ."    }\n"
        ."\n"
        ."    public function destroy({$Model} \${$model})\n"
        ."    {\n"
        ."        \${$model}->delete();\n"
        ."\n"
        ."        return response()->json(null , 204) ;\n"
        ."    }\n"
        ."}" ;
    }
}
