<?php

use App\Models\User;
use App\Models\Company;
use App\Constants\Disks;
use App\Models\Freelancer;
use App\Constants\Defaults;
use App\Models\GalleryImage;
use App\Services\xmlService;
use Illuminate\Http\Request;
use App\Services\imageService;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Company\CompanyResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

require 'Api/log.php' ;

require 'Api/socialite.php' ;

require 'Api/otp.php' ;

require 'Api/render-commands.php';

require 'Api/category.php' ;

require 'Api/storage.php' ;

require 'Api/company.php' ;

require 'Api/client.php' ;

require 'Api/freelancer.php' ;


Route::post('test' , function(Request $request){
  return Company::findOrFail(1)->delete();
});

Route::get('test' , function(){
  $path = "/home/saad/Desktop/Freelancer/datasets/technology-skills-25000.xlsx";
  $row = 1; 
  if (($handle = fopen($path, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $num = count($data);
      $row++;

      if($row>50) return 'end' ;
      var_dump($data);
      
      // for ($c=0; $c < $num; $c++) {
      //     //if($c==4)var_dump($data[$c]) ;
      // }
    }
    fclose($handle);
    return $row;
    
}
});