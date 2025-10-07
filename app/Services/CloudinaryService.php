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
        // Try CLOUDINARY_URL first (official Cloudinary way)
        $cloudinaryUrl = env('CLOUDINARY_URL');
        
        if (!$cloudinaryUrl) {
            // Fallback to individual credentials
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');
            
            $cloudinaryUrl = sprintf(
                'cloudinary://%s:%s@%s',
                $apiKey,
                $apiSecret,
                $cloudName
            );
        }
        
        Log::info('CloudinaryService __construct called');
        Log::info('Using CLOUDINARY_URL: ' . (env('CLOUDINARY_URL') ? 'YES' : 'NO (using individual vars)'));
        
        $this->cloudinary = new Cloudinary($cloudinaryUrl);
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
            $fileName = $file->getClientOriginalName();
            
            // Log upload attempt
            Log::info('📤 Starting Cloudinary upload: ' . $fileName, [
                'file_size' => round($file->getSize() / 1024, 2) . ' KB',
                'mime_type' => $file->getMimeType(),
                'folder' => $folder
            ]);

            $uploadApi = new UploadApi();
            
            $result = $uploadApi->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'auto',
                'quality' => 'auto:good',
                'fetch_format' => 'auto'
            ]);

            $url = $result['secure_url'] ?? null;
            
            if ($url) {
                // Log success with URL
                Log::info('✅ Cloudinary upload SUCCESS: ' . $fileName);
                Log::info('🔗 Cloudinary URL: ' . $url);
                Log::info('📦 Public ID: ' . ($result['public_id'] ?? 'N/A'));
                
                // Print to console/output
                echo "\n✅ Successfully uploaded: {$fileName}\n";
                echo "🔗 Cloudinary URL: {$url}\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            } else {
                Log::warning('⚠️ Upload completed but no URL returned for: ' . $fileName);
            }

            return $url;
        } catch (\Exception $e) {
            Log::error('❌ Cloudinary upload FAILED: ' . $file->getClientOriginalName(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            echo "\n❌ Failed to upload: {$file->getClientOriginalName()}\n";
            echo "Error: {$e->getMessage()}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            
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
        $totalFiles = count($files);
        
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        Log::info("📦 Starting batch upload to Cloudinary: {$totalFiles} file(s)");
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📦 Starting batch upload to Cloudinary\n";
        echo "Total files: {$totalFiles}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        foreach ($files as $index => $file) {
            $fileNum = $index + 1;
            Log::info("⬆️ Uploading file {$fileNum} of {$totalFiles}: " . $file->getClientOriginalName());
            
            echo "\n⬆️  Uploading file {$fileNum}/{$totalFiles}...\n";
            
            $url = $this->upload($file, $folder);
            if ($url) {
                $urls[] = $url;
                Log::info("✅ File {$fileNum} uploaded successfully to Cloudinary");
            } else {
                Log::error("❌ File {$fileNum} upload failed");
                echo "❌ Failed to upload file {$fileNum}\n";
            }
        }
        
        $successCount = count($urls);
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        Log::info("📊 Upload complete: {$successCount} of {$totalFiles} files uploaded successfully");
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 UPLOAD SUMMARY\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ Successfully uploaded: {$successCount}/{$totalFiles} files\n";
        
        if ($successCount > 0) {
            echo "\n📸 Uploaded URLs:\n";
            foreach ($urls as $i => $url) {
                echo "  " . ($i + 1) . ". {$url}\n";
            }
        }
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        return $urls;
    }

    /**
     * Check if Cloudinary is configured
     * 
     * @return bool
     */
    public static function isConfigured(): bool
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        $isConfigured = !empty($cloudName) && !empty($apiKey) && !empty($apiSecret);
        
        // Log configuration status
        Log::info('🔧 Cloudinary configuration check', [
            'is_configured' => $isConfigured ? 'YES ✅' : 'NO ❌',
            'has_cloud_name' => !empty($cloudName) ? 'YES' : 'NO',
            'has_api_key' => !empty($apiKey) ? 'YES' : 'NO',
            'has_api_secret' => !empty($apiSecret) ? 'YES' : 'NO',
            'cloud_name' => $cloudName ? substr($cloudName, 0, 5) . '***' : 'MISSING'
        ]);
        
        return $isConfigured;
    }
}

