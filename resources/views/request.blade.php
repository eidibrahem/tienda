@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 grid md:grid-cols-2 gap-8">
  
  <div class="bg-white rounded-2xl shadow p-5">
    {{-- ✅ لو فيه فيديو اعرضه، ولو مفيش اعرض صورة --}}
    @if($template->video_url)
      <video controls class="w-full rounded-xl mb-4">
        <source src="{{ asset($template->video_url) }}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    @else
      <img src="{{ $template->thumbnail_url ? asset($template->thumbnail_url) : asset('/images/placeholder.jpg') }}" 
           class="w-full rounded-xl mb-4">
    @endif

    <h2 class="text-xl font-bold">{{ $template->name }}</h2>
    <p class="text-gray-600">{{ $template->description }}</p>
    <div class="mt-3 font-semibold text-amber-600">
      {{ number_format($template->price, 2) }} AED
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow p-5">
    <form action="{{ route('orders.store', $template) }}" method="post" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div>
        <label class="block font-semibold">Upload Photos (JPG/PNG/GIF, max 10MB each)</label>
        <input type="file" name="photos[]" multiple class="mt-1 w-full border rounded p-2">
        @error('photos.*') 
          <p class="text-red-600 text-sm">{{ $message }}</p> 
        @enderror
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold">Your Name</label>
          <input name="name" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="block font-semibold">Email</label>
          <input name="email" type="email" class="w-full border rounded p-2" required>
        </div>
      </div>

      <div>
        <label class="block font-semibold">Country</label>
        <input name="country" class="w-full border rounded p-2">
      </div>

      <div>
        <label class="block font-semibold">Video Description</label>
        <textarea name="description" rows="4" class="w-full border rounded p-2"></textarea>
      </div>

      <button class="w-full bg-amber-500 hover:bg-amber-600 text-white rounded-lg py-3 font-semibold">
        Pay {{ number_format($template->price, 2) }} AED – Complete Order
      </button>
    </form>
  </div>
</div>
@endsection
