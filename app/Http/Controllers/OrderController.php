<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Template;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\CloudinaryService;
use App\Mail\OrderDeliveredMail;

class OrderController extends Controller {
    public function create(Template $template) {
        abort_unless($template->is_active, 404);
        return view('request', compact('template'));
    }

    public function store(Request $r, Template $template) {
        $data = $r->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'country'     => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'photos.*'    => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240', // 10MB
        ]);

        $paths = [];
        if ($r->hasFile('photos')) {
            $uploadedFiles = $r->file('photos');
            $fileCount = count($uploadedFiles);
            
            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::info("📸 Processing {$fileCount} photo(s) for upload...");
            
            // Use Cloudinary if configured, otherwise use local storage
            if (CloudinaryService::isConfigured()) {
                Log::info('🔵 Cloudinary is configured - Starting upload...');
                echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                echo "🔵 CLOUDINARY UPLOAD PROCESS\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                
                $cloudinary = new CloudinaryService();
                $paths = $cloudinary->uploadMultiple($uploadedFiles);
                
                // Print uploaded URLs for debugging
                if (!empty($paths)) {
                    Log::info('✅ Cloudinary Upload SUCCESS! Total URLs: ' . count($paths));
                    
                    echo "\n✅ ALL PHOTOS UPLOADED TO CLOUDINARY!\n";
                    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    echo "📋 CLOUDINARY URLs TO BE SAVED TO DATABASE:\n";
                    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                    
                    foreach ($paths as $index => $url) {
                        $num = $index + 1;
                        Log::info("📸 Photo {$num} URL: {$url}");
                        echo "  {$num}. {$url}\n";
                    }
                    
                    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
                } else {
                    Log::warning('⚠️ Cloudinary upload returned empty array!');
                    echo "\n⚠️  WARNING: No photos were uploaded to Cloudinary!\n";
                }
            } else {
                // Fallback to local storage for development
                Log::info('📁 Using local storage (Cloudinary not configured)');
                echo "\n⚠️  Cloudinary NOT configured - using local storage\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                
                foreach ($uploadedFiles as $file) {
                    $path = $file->store('uploads/orders', 'public');
                    $paths[] = $path;
                    Log::info("💾 Local storage path: " . $path);
                    echo "💾 Saved locally: {$path}\n";
                }
                
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            }
            
            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        }

        // Create order with photo URLs
        Log::info("💾 Creating order in database...");
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "💾 SAVING TO DATABASE\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        $order = Order::create([
            'template_id' => $template->id,
            'name'        => $data['name'],
            'email'       => $data['email'],
            'country'     => $data['country'] ?? null,
            'description' => $data['description'] ?? null,
            'photos'      => $paths,
            'price'       => $template->price, // snapshot
            'status'      => 'pending'
        ]);
        
        // Verify photos were saved to database
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        Log::info('✅ Order created successfully!');
        Log::info('📦 Order ID: ' . $order->id);
        Log::info('👤 Customer: ' . $order->name . ' (' . $order->email . ')');
        Log::info('📸 Total photos: ' . count($order->photos));
        Log::info('💰 Price: ' . $order->price . ' AED');
        
        echo "✅ Order saved successfully!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Order ID: {$order->id}\n";
        echo "Customer: {$order->name}\n";
        echo "Email: {$order->email}\n";
        echo "Photos saved: " . count($order->photos) . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        // Print each photo URL from database to verify
        if (!empty($order->photos)) {
            Log::info('📋 Photo URLs saved in database:');
            echo "\n📋 PHOTO URLs IN DATABASE (MySQL):\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            
            foreach ($order->photos as $index => $photoUrl) {
                $num = $index + 1;
                Log::info("  {$num}. {$photoUrl}");
                echo "  {$num}. {$photoUrl}\n";
            }
            
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        } else {
            Log::warning('⚠️  No photos were saved to database!');
            echo "⚠️  WARNING: No photos in database!\n";
        }
        
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // Redirect to home page after creating order
        return redirect()->route('home')->with('success', 'Your order has been submitted successfully!');
    }

    // لوحة بسيطةs
    public function index() {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    // Dashboard with all orders
    public function dashboard() {
        $orders = Order::with('template')->latest()->paginate(20);
        
        // Calculate statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedToday = Order::where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();
        $totalRevenue = Order::sum('price');
        
        return view('dashboard', compact('orders', 'totalOrders', 'pendingOrders', 'completedToday', 'totalRevenue'));
    }

    // Update order status
    public function updateStatus(Request $request, Order $order) {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,delivered'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // Send email if status changed to delivered
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            try {
                // Queue the email to avoid blocking the request
                Mail::to($order->email)->queue(new OrderDeliveredMail($order));
                Log::info("📧 Delivery email queued for: {$order->email} for Order ID: {$order->id}");
            } catch (\Exception $e) {
                Log::error("❌ Failed to queue delivery email to: {$order->email}. Error: " . $e->getMessage());
                // Don't fail the status update if email fails
            }
        }

        // Redirect back with status_updated parameter to bypass password check
        return redirect()->to(route('dashboard') . '?status_updated=true')->with('success', 'Order status updated successfully!');
    }
}
