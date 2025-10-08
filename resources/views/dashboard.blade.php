@extends('layouts.app')

@section('content')
<script>
    // Check if admin is authenticated
    function checkAdminAuth() {
        const isAuthenticated = sessionStorage.getItem('adminAuthenticated');
        const urlParams = new URLSearchParams(window.location.search);
        const statusUpdated = urlParams.get('status_updated');
        
        if (statusUpdated === 'true' || isAuthenticated === 'true') {
            showDashboard();
        } else {
            showPasswordModal();
        }
    }
    
    function showPasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.remove('hidden');
    }
    
    function hidePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.add('hidden');
    }
    
    function authenticateAdmin() {
        const password = document.getElementById('adminPassword').value.trim();
        const correctPassword = 'admin123';
        
        if (password === correctPassword) {
            sessionStorage.setItem('adminAuthenticated', 'true');
            hidePasswordModal();
            showDashboard();
        } else {
            alert('Incorrect password. Please try again.');
            document.getElementById('adminPassword').value = '';
        }
    }
    
    function showDashboard() {
        const dashboardContent = document.getElementById('dashboardContent');
        dashboardContent.classList.remove('hidden');
    }
    
    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', checkAdminAuth);
</script>

<!-- Password Protection Modal -->
<div id="passwordModal" class="fixed inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black bg-opacity-95 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl transform transition-all duration-300 scale-100" style="width: 350px; padding: 25px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);">
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-3 tracking-tight">Admin Dashboard</h2>
            <p class="text-gray-500 text-sm font-medium">Enter password to access</p>
        </div>
        
        <form onsubmit="event.preventDefault(); authenticateAdmin();" class="space-y-6">
            <div class="relative">
                <label for="adminPassword" class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Password</label>
                <div class="relative">
                    <input 
                        type="password"
                        id="adminPassword" 
                        class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-400 transition-all duration-300 bg-gray-50 focus:bg-white font-medium placeholder-gray-400"
                        placeholder="Enter admin password"
                        required
                        style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
                    >
                </div>
            </div>
            
            <button 
                type="submit"
                class="w-full text-white py-3 px-4 rounded-xl font-bold text-base shadow-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl focus:ring-4 focus:ring-gray-200"
                style="background-color: #1a4241; box-shadow: 0 10px 15px -3px rgba(26, 66, 65, 0.4), 0 4px 6px -2px rgba(26, 66, 65, 0.05);"
            >
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Access Dashboard
                </span>
            </button>
        </form>
    </div>
</div>

<div id="dashboardContent" class="hidden">
<style>
    :root {
        --background: #fdfcf9;
        --foreground: #292524;
        --primary: #db732a;
        --primary-light: #f4a168;
        --primary-dark: #c65d1f;
        --secondary: #1a4241;
        --secondary-light: #0d9488;
        --accent: #f7ebd5;
        --accent-light: #fdfcf9;
        --surface: #fff;
        --border: #f8ecd6;
        --text-light: #57534e;
        --shadow-sm: 0 1px 2px 0 rgba(26, 66, 65, .05);
        --shadow-md: 0 4px 6px -1px rgba(26, 66, 65, .1), 0 2px 4px -1px rgba(26, 66, 65, .06);
        --shadow-lg: 0 10px 15px -3px rgba(26, 66, 65, .1), 0 4px 6px -2px rgba(26, 66, 65, .05);
    }

    /* Dashboard Custom Styles */
    .dashboard-container {
        background: var(--background);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .stats-card {
        background: var(--surface);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-lg);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .stats-card-1 {
        --card-color-1: var(--primary);
        --card-color-2: var(--primary-light);
    }
    
    .stats-card-2 {
        --card-color-1: var(--secondary);
        --card-color-2: var(--secondary-light);
    }
    
    .stats-card-3 {
        --card-color-1: var(--primary-dark);
        --card-color-2: var(--primary);
    }
    
    .stats-card-4 {
        --card-color-1: var(--secondary);
        --card-color-2: var(--primary);
    }
    
    .stats-number {
        background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 900;
        font-size: 2.5rem;
        line-height: 1;
    }
    
    .stats-label {
        color: var(--text-light);
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .stats-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    .order-card {
        background: var(--surface);
        border-radius: 1rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .order-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--status-color);
    }
    
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .status-pending {
        --status-color: var(--primary);
    }
    
    .status-processing {
        --status-color: var(--secondary);
    }
    
    .status-completed {
        --status-color: var(--secondary-light);
    }
    
    .status-delivered {
        --status-color: var(--primary-dark);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge-pending {
        background: var(--accent);
        color: var(--primary-dark);
    }
    
    .status-badge-processing {
        background: var(--accent);
        color: var(--secondary);
    }
    
    .status-badge-completed {
        background: var(--accent);
        color: var(--secondary-light);
    }
    
    .status-badge-delivered {
        background: var(--accent);
        color: var(--primary-dark);
    }
    
    .dashboard-header {
        background: var(--surface);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
    }
    
    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--secondary-light);
        color: white;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .live-dot {
        width: 0.5rem;
        height: 0.5rem;
        background: white;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--accent-light);
        border-radius: 1rem;
        border: 2px dashed var(--border);
    }
    
    .empty-icon {
        width: 4rem;
        height: 4rem;
        color: var(--text-light);
        margin: 0 auto 1rem;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .success-message {
        background: linear-gradient(135deg, var(--secondary-light), var(--secondary));
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
        border: none;
    }
    
    .dashboard-container h3 {
        color: var(--foreground);
    }
    
    .order-card h4 {
        color: var(--foreground);
    }
    
    .order-card p {
        color: var(--text-light);
    }
</style>
<div class="dashboard-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div> 
        @endif

        <div class="dashboard-header">
            <!-- Statistics Cards -->
            <div class="flex flex-wrap gap-4 mb-8">
                <!-- Total Orders -->
                <div class="stats-card stats-card-1 rounded-xl p-6 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="stats-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="stats-label">Total Orders</p>
                            <p class="stats-number">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="stats-card stats-card-2 rounded-xl p-6 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="stats-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="stats-label">Pending Orders</p>
                            <p class="stats-number">{{ $pendingOrders }}</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Today -->
                <div class="stats-card stats-card-3 rounded-xl p-6 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="stats-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="stats-label">Completed Today</p>
                            <p class="stats-number">{{ $completedToday }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="stats-card stats-card-4 rounded-xl p-6 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="stats-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="stats-label">Total Revenue</p>
                            <p class="stats-number">AED {{ number_format($totalRevenue, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Orders List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($orders as $order)
                    <div class="order-card status-{{ $order->status }} p-6">
                        <div class="flex justify-between items-start gap-4">
                            <!-- Left side: Status and Order Info -->
                            <div class="flex gap-4 flex-1 min-w-0">
                                <!-- Status Badge -->
                                <div class="flex-shrink-0 pt-1">
                                    @if($order->status == 'completed')
                                        <span class="status-badge status-badge-completed">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                    @elseif($order->status == 'delivered')
                                        <span class="status-badge status-badge-delivered">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                            </svg>
                                            Delivered
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="status-badge status-badge-processing">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Processing
                                        </span>
                                    @else
                                        <span class="status-badge status-badge-pending">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </div>

                                <!-- Order Details -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $order->name }}</h4>
                                    <p class="text-gray-700 mt-0.5">{{ $order->email }}</p>
                                    @if($order->description)
                                        <p class="text-sm text-gray-500 italic mt-1">"{{ $order->description }}"</p>
                                    @endif
                                    @if($order->photos && count($order->photos) > 0)
                                        <div class="flex items-center gap-1.5 mt-2 text-xs text-gray-600 bg-gray-50 rounded-md px-2 py-1 inline-flex">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ count($order->photos) }} {{ Str::plural('photo', count($order->photos)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Right side: Price, Date and Actions -->
                            <div class="flex items-center gap-4 flex-shrink-0">
                                <!-- Price and Date -->
                                <div class="text-right">
                                    <p class="font-bold text-xl text-gray-900">AED {{ number_format($order->price, 0) }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y, g:i A') }}</p>
                                </div>
                                
                                <!-- Status Update Form -->
                                <form method="POST" action="{{ route('orders.update-status', $order->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col gap-2">
                                    @if($order->photos && count($order->photos) > 0)
                                        @php
                                            // Check if it's a Cloudinary URL (starts with http/https) or local path
                                            $photoUrl = $order->photos[0];
                                            $isCloudinary = str_starts_with($photoUrl, 'http://') || str_starts_with($photoUrl, 'https://');
                                            $displayUrl = $isCloudinary ? $photoUrl : Storage::url($photoUrl);
                                            $fileName = 'order-' . $order->id . '-photo-1.jpg';
                                        @endphp
                                        
                                        <!-- View Image -->
                                        <a href="{{ $displayUrl }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 text-sm font-medium px-3 py-1.5 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            View Image
                                        </a>
                                        
                                        <!-- Download -->
                                        <button 
                                            class="download-single-btn inline-flex items-center justify-center gap-1.5 text-green-600 hover:text-green-700 hover:bg-green-50 text-sm font-medium px-3 py-1.5 rounded-md transition-colors"
                                            data-url="{{ $displayUrl }}"
                                            data-filename="{{ $fileName }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </button>
                                        
                                        @if(count($order->photos) > 1)
                                            <!-- Download All -->
                                            <button 
                                                class="download-all-btn inline-flex items-center justify-center gap-1.5 text-purple-600 hover:text-purple-700 hover:bg-purple-50 text-sm font-medium px-3 py-1.5 rounded-md transition-colors"
                                                data-order-id="{{ $order->id }}"
                                                data-photos="{{ json_encode($order->photos) }}"
                                                data-is-cloudinary="{{ $isCloudinary ? '1' : '0' }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                                </svg>
                                                All ({{ count($order->photos) }})
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-semibold">No orders found</p>
                        <p class="text-gray-400 text-sm mt-1">Orders will appear here once created</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if(isset($orders) && $orders->hasPages())
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white rounded-lg shadow-lg p-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

<script>
// Function to download image from URL
async function downloadImage(url, filename) {
    try {
        // Show loading indicator
        const response = await fetch(url);
        const blob = await response.blob();
        const blobUrl = window.URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        
        // Clean up the blob URL
        window.URL.revokeObjectURL(blobUrl);
    } catch (error) {
        console.error('Error downloading image:', error);
        alert('Error downloading image. Please try again.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Download single photo
    const singleDownloadBtns = document.querySelectorAll('.download-single-btn');
    singleDownloadBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const filename = this.dataset.filename;
            downloadImage(url, filename);
        });
    });
    
    // Download all photos for an order
    const downloadAllBtns = document.querySelectorAll('.download-all-btn');
    downloadAllBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const photos = JSON.parse(this.dataset.photos);
            const isCloudinary = this.dataset.isCloudinary === '1';
            
            photos.forEach((photo, index) => {
                setTimeout(() => {
                    const url = isCloudinary ? photo : '/storage/' + photo;
                    const filename = 'order-' + orderId + '-photo-' + (index + 1) + '.jpg';
                    downloadImage(url, filename);
                }, index * 500);
            });
        });
    });
});
</script>
@endsection
