<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Template;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

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
            foreach ($r->file('photos') as $file) {
                $paths[] = $file->store('uploads/orders', 'public'); // storage/app/public/...
            }
        }

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

        return redirect()->route('orders.pay', $order);
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

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
