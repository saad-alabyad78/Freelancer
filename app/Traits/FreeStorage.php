<?php

namespace App\Traits;
use App\Constants\Disks;
use Illuminate\Support\Facades\Storage;
trait FreeStorage
{
    /**
     * directories to clean up images from
     * 
     * @var array
     */
    protected $stroageDirectories = [
        Disks::COMPANY ,
    ];

    /**
     * This method is called before each test method is executed.
     */
    protected function setUp(): void
    {
        //clean before running each test
        parent::setUp();
        $this->deleteImages();
    }

    /**
     * This method is called after each test method is executed.
     */
    protected function tearDown(): void
    {
        //clean after running each test
        $this->deleteImages();
        parent::tearDown();
    }

    protected function deleteImages()
    {
        foreach($this->stroageDirectories as $storageDirectory)
        {
            $files = Storage::files($storageDirectory) ;

            foreach($files as $file)
            {
                Storage::delete($file) ;
            }
        }
    }

}


