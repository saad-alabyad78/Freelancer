<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Constants\Defaults;
use App\Constants\Disks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CreateCompanyImageCommandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and company
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create(['profile_image' => Defaults::COMPANY_PROFILE_IMAGE]);
        $this->user->role_id = $this->company->id;
        $this->user->save();

        // Authenticate the user
        $this->actingAs($this->user);

        // Fake the storage disk
        Storage::fake(Disks::COMPANY);
    }

    public function testProfileImageSuccessfullyUpdated()
    {
        // Create a fake image
        $file = UploadedFile::fake()->image('profile.jpg');

        // Make the request
        $response = $this->json('POST', '/api/company/image/profile', [
            'image' => $file,
        ]);

        // Assert the response status
        $response->assertStatus(201);

        // Get the updated company
        $company = Company::find($this->company->id);

        // Assert the profile image has been updated
        $this->assertNotEquals(Defaults::COMPANY_PROFILE_IMAGE, $company->profile_image);

        // Assert the file exists in the storage
        Storage::disk(Disks::COMPANY)->assertExists($company->profile_image);

        // Assert the correct URL is returned
        $response->assertJson([
            'profile_image_url' => $company->profile_image_url,
        ]);
    }

    public function testProfileImageNotProvided()
    {
        // Make the request without an image
        $response = $this->json('POST', '/api/company/image/profile');

        // Assert the response status
        $response->assertStatus(422);

        // Assert validation error
        $response->assertJsonValidationErrors(['image']);
    }

    public function testOldProfileImageDeleted()
    {
        // Set an old profile image
        $this->company->profile_image = 'old_image.jpg';
        $this->company->save();

        // Fake the old image in the storage
        Storage::disk(Disks::COMPANY)->put('old_image.jpg', 'old_image_content');

        // Create a fake new image
        $file = UploadedFile::fake()->image('new_profile.jpg');

        // Make the request
        $response = $this->json('POST', '/api/company/image/profile', [
            'image' => $file,
        ]);

        // Assert the response status
        $response->assertStatus(201);

        // Get the updated company
        $company = Company::find($this->company->id);

        // Assert the profile image has been updated
        $this->assertNotEquals('old_image.jpg', $company->profile_image);

        // Assert the new file exists in the storage
        Storage::disk(Disks::COMPANY)->assertExists($company->profile_image);

        // Assert the old file is deleted from the storage
        Storage::disk(Disks::COMPANY)->assertMissing('old_image.jpg');

        // Assert the correct URL is returned
        $response->assertJson([
            'profile_image_url' => $company->profile_image_url,
        ]);
    }
}
