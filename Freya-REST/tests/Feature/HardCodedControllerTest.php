<?php

namespace Tests\Feature;

use App\Models\Stage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HardCodedControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_documentation_link_with_fake_disk(): void
    {
        Storage::fake('public');
        $filePath = 'documentation/FreyasGardenDocumentation.docx';
        Storage::disk('public')->put($filePath, 'dummy content');

        $response = $this->get('/api/documentation');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Documentation available at the provided URL',
            'data' => 'http://localhost:8069/storage/documentation/FreyasGardenDocumentation.docx',
        ]);
    }

    public function test_get_documentation_link_with_real_disk(): void
    {
        $response = $this->get('/api/documentation');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Documentation available at the provided URL',
            'data' => 'http://localhost:8069/storage/documentation/FreyasGardenDocumentation.docx',
        ]);
    }

    public function test_get_placeholders(): void
    {
        // Mock the storage disk
        Storage::fake('public');

        // Create fake placeholder files
        $files = [
            'placeholders/image1.jpg',
            'placeholders/image2.png',
            'placeholders/image3.gif',
        ];

        foreach ($files as $file) {
            Storage::disk('public')->put($file, 'dummy content');
        }

        // Call the API endpoint
        $response = $this->get('/api/images/placeholders');

        // Assert the response status
        $response->assertStatus(200);

        // Assert the response JSON structure
        $response->assertJsonStructure([
            'message',
            'data' => [
                'placeholders',
                'count',
            ],
        ]);

        // Assert the count of files
        $response->assertJson([
            'status' => 200,
            'message' => 'Placeholders available at the provided URLs',
            'data' => [
                'placeholders' => [
                    'http://localhost:8069/storage/placeholders/image1.jpg',
                    'http://localhost:8069/storage/placeholders/image2.png',
                    'http://localhost:8069/storage/placeholders/image3.gif',
                ],
                'count' => count($files),
            ],
        ]);

        // Assert the file URLs
        $expectedUrls = array_map(function ($file) {
            return asset("storage/{$file}");
        }, $files);
        $response->assertJson([
            'data' => [
                'placeholders' => $expectedUrls,
            ],
        ]);
    }
}
