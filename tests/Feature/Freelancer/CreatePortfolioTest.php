<?php

namespace Tests\Feature\Freelancer;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\Freelancer;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePortfolioTest extends TestCase
{
    use RefreshDatabase ;
    private Freelancer $freelancer ;
    private User $user ;
    public function setUp(): void
    {
        parent::setUp() ;
        $this->seed() ;
        
        $this->user = User::factory()->create() ;

        $this->freelancer = Freelancer::factory()->create() ;

        $this->freelancer->user()->save($this->user) ;
        
        $this->actingAs($this->user ) ;
        
    } 
    public function test_create_portfolio(): void
    {
        
        $data = [
            'title' => 'hihi' ,
            'url' => null,
            'date' => Carbon::now()->subYears(15),
            'description' => 'af dsf  dsf d fds df ds fds ',
            //'files' => [UploadedFile::fake()->createWithContent('hi.pdf' , "sdkfkdsfklsdf")],
            //'images' => [UploadedFile::fake()->image('hi.png' , 200 , 200)->size(2000)],
            'skill_ids' => [1,2,3,4,5]
        ] ;
        $response = $this->postJson('api/freelancer/portfolio/store' , $data);
            
        
        $response->assertStatus(201) ;

        $portfolio = Portfolio::where('title' , 'hihi')->first() ;

        $portfolio->delete() ;
    }
}
