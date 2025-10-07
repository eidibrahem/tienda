<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    /**
     * Upload file to Cloudinary
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null URL of uploaded file
     */
    public function upload(UploadedFile $file, string $folder = 'tienda/orders'): ?string
    {
        try {
            $uploadApi = new UploadApi();
            
            $result = $uploadApi->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'auto',
                'quality' => 'auto:good',
                'fetch_format' => 'auto'
            ]);

            return $result['secure_url'] ?? null;
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload multiple files
     * 
     * @param array $files
     * @param string $folder
     * @return array URLs of uploaded files
     */
    public function uploadMultiple(array $files, string $folder = 'tienda/orders'): array
    {
        $urls = [];
        
        foreach ($files as $file) {
            $url = $this->upload($file, $folder);
            if ($url) {
                $urls[] = $url;
            }
        }
        
        return $urls;
    }

    /**
     * Check if Cloudinary is configured
     * 
     * @return bool
     */
    public static function isConfigured(): bool
    {
        return !empty(env('CLOUDINARY_CLOUD_NAME')) 
            && !empty(env('CLOUDINARY_API_KEY')) 
            && !empty(env('CLOUDINARY_API_SECRET'));
    }
}

