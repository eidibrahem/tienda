@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
  <div class="text-center mb-8">
    <h1 class="text-3xl font-bold">Transform Your Memories Into Beautiful Videos</h1>
    <p class="text-gray-600">Choose your style and order your custom video.</p>
  </div>

  <div class="grid md:grid-cols-3 gap-6">
    @foreach($templates as $tpl)
      <div class="bg-white rounded-2xl shadow p-3 overflow-hidden">
        
        {{-- ✅ لو فيه فيديو، اعرضه --}}
        @if($tpl->video_url)
          <video controls class="w-full h-64 rounded-xl object-cover">
            <source src="{{ asset($tpl->video_url) }}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        @else
          {{-- ❌ لو مفيش فيديو، اعرض صورة --}}
          <img src="{{ $tpl->thumbnail_url ? asset($tpl->thumbnail_url) : asset('/images/placeholder.jpg') }}" 
               class="w-full h-64 object-cover rounded-xl">
        @endif

        <div class="p-2">
          <h3 class="font-semibold">{{ $tpl->name }}</h3>
          <p class="text-sm text-gray-600">{{ $tpl->description }}</p>
          <div class="flex items-center justify-between mt-3">
            <span class="font-bold text-amber-600">{{ number_format($tpl->price,2) }} AED</span>
            <a href="{{ route('orders.create', $tpl) }}"
               class="px-3 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600">
              Request Similar
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
