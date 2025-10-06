@extends('layouts.app')

@section('content')
<style>
  :root{
    --background:#fdfcf9;
    --secondary:#1a4241;
    --primary:#db732a; --primary-dark:#c65d1f;
    --accent:#F7EBDC; --accent-light:#fef7f0;
    --surface:#fff; --border:#f8ecd6; --muted:#4b6968;
    --shadow-md:0 4px 6px -1px rgba(26,66,65,.10),0 2px 4px -1px rgba(26,66,65,.06);
    --shadow-lg:0 12px 18px -5px rgba(26,66,65,.14),0 6px 10px -4px rgba(26,66,65,.08);
  }

  .bg-page{ background:linear-gradient(135deg,var(--background) 0%, #f7ebd5 50%, var(--accent-light) 100%); }
  body{ color:var(--secondary); }

  .card{ background:var(--surface); border:1px solid var(--border); border-radius:1rem; box-shadow:var(--shadow-md); transition:.25s; }
  .card:hover{ box-shadow:var(--shadow-lg); transform:translateY(-3px); }

  .btn-primary{ background:var(--primary); color:#fff; border-radius:.65rem; font-weight:600; }
  .btn-primary:hover{ background:var(--primary-dark); }

  /* فيديو 9:16 */
  .aspect-9-16{ position:relative; width:100%; }
  .aspect-9-16::before{ content:""; display:block; padding-top:177.78%; }
  .aspect-9-16 > *{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; border-radius:.9rem; }

  /* جريد 1/2/3 */
  .templates-grid{ display:grid; gap:1.75rem; grid-template-columns:1fr; }
  @media (min-width:640px){ .templates-grid{ grid-template-columns:repeat(2,1fr); } }
  @media (min-width:1024px){ .templates-grid{ grid-template-columns:repeat(3,1fr); } }

  /* عناوين */
  .brand-title{ color:#0F3D3E; font-weight:900; font-size:clamp(1.8rem, 2.2vw + 1rem, 3rem); line-height:1.1; letter-spacing:-.02em; text-align:center; }
  .brand-sub{ color:var(--muted); font-weight:600; font-size:clamp(.95rem, .6vw + .8rem, 1.25rem); text-align:center; }

  .hero-title{ font-weight:900; font-size:clamp(1.8rem, 2.2vw + 1rem, 3rem); line-height:1.3; letter-spacing:-.02em; text-align:center; margin-bottom: 20px ; }
  .hero-sub{ color:var(--muted); font-weight:500; font-size:clamp(1rem, 1.2vw + .6rem, 1.25rem); text-align:center; }

  .section-title{ font-weight:800; font-size:clamp(1.6rem, 1.4vw + 1rem, 2.25rem); text-align:center;margin-bottom: 20px ; }

  /* How It Works */
  .how-title{ font-weight:900; font-size:clamp(1.8rem, 1.8vw + 1rem, 2.5rem); text-align:center; }
  .how-sub{ color:var(--muted); font-weight:600; font-size:clamp(1rem, .8vw + .8rem, 1.125rem); text-align:center; }
  .how-grid{ display:grid; grid-template-columns:1fr; gap:2rem; }
  @media (min-width:768px){ .how-grid{ grid-template-columns:repeat(3,1fr); gap:2.25rem; } }
  .how-number{
    width:clamp(56px, 6.5vw, 72px); height:clamp(56px, 6.5vw, 72px);
    background:linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
    border:3px solid var(--primary); border-radius:999px;
    display:flex; align-items:center; justify-content:center;
    box-shadow:var(--shadow-lg);
  }
  .how-number span{ color:var(--primary); font-weight:900; font-size:clamp(22px, 2.2vw, 28px); text-align:center; }

  /* توحيد التوسيط */
  h1,h2,h3,h4,h5,h6,p,span,div{ text-align:center; }

  /* مسافة 70px بين How It Works والـ Footer */
  .gap-to-footer{ margin-bottom:70px; }
  @media (min-width:1024px){ .gap-to-footer{ margin-bottom:40px;margin-top: 40px; } }
</style>

<div class="min-h-screen bg-page">
  <!-- Header -->
  <header class="bg-white border-b" style="border-color:var(--border)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center flex flex-col items-center">
     <img src="{{ asset('assets/logo.webp') }}" alt="Tienda Logo"
     class="rounded-full mb-4 shadow"
     style="width:125px !important; height:125px !important; box-shadow:var(--shadow-lg)">

        <h1 class="brand-title">Tienda</h1>
        <p class="brand-sub">Order custom videos tailored for you</p>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero -->
    <section class="mb-16 sm:mb-20">
      <h4 class="hero-title mb-5">Transform Your Memories Into Beautiful Videos</h4>
      <p class="hero-sub max-w-3xl mx-auto">
        Choose from our collection of professionally crafted video templates. Upload your photos and let us create a stunning personalized video just for you.
      </p>
    </section>

    <!-- Choose Your Style -->
    <section class="mb-16 sm:mb-20">
      <h3 class="section-title mb-10">Choose Your Style</h3>

      <div class="templates-grid">
        @foreach($templates as $tpl)
          <a href="{{ route('orders.create', $tpl) }}" class="card overflow-hidden block hover:scale-105 transition-transform duration-300 cursor-pointer">
            <!-- Media -->
            <div class="aspect-9-16 group">
              @if($tpl->video_url)
                <video autoplay muted loop playsinline preload="metadata" controls
                       poster="{{ $tpl->thumbnail_url ? asset($tpl->thumbnail_url) : '' }}">
                  <source src="{{ asset($tpl->video_url) }}" type="video/mp4">
                </video>
              @else
                <img src="{{ $tpl->thumbnail_url ? asset($tpl->thumbnail_url) : asset('images/placeholder.jpg') }}"
                     alt="{{ $tpl->name }}">
              @endif

              <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center rounded-[.9rem]">
                <span class="opacity-0 group-hover:opacity-100 transition bg-white/90 px-4 py-2 rounded-md text-sm font-semibold text-[var(--primary)]">
                  Tap to Request Similar
                </span>
              </div>
            </div>

            <!-- Body -->
            <div class="p-6">
              <h4 class="text-xl font-extrabold mb-2">{{ $tpl->name }}</h4>
              <p class="text-base text-[var(--muted)] mb-5 font-medium">{{ $tpl->description }}</p>

              <div class="flex items-center justify-between">
                <span class="text-2xl font-extrabold text-[var(--primary)]">
                  {{ number_format($tpl->price, 0) }} AED
                </span>
                <span class="btn-primary px-6 py-2 shadow-sm inline-block">
                  Request Similar
                </span>
              </div>
            </div>
            
          </a>
        @endforeach
      </div>
    </section>

    <!-- How It Works -->
    <section class="card px-6 sm:px-10 py-10 sm:py-14 gap-to-footer">
      <h3 class="how-title mb-3">How It Works</h3>
      <p class="how-sub mb-10">Simple steps to get your personalized video</p>

      <div class="how-grid">
        <div>
          <div class="how-number mx-auto mb-4"><span>1</span></div>
          <h4 class="text-lg font-bold mb-2">Choose Your Style</h4>
          <p class="text-[var(--muted)] font-medium">Browse our sample videos and select the style that matches your vision.</p>
        </div>

        <div>
          <div class="how-number mx-auto mb-4"><span>2</span></div>
          <h4 class="text-lg font-bold mb-2">Upload & Customize</h4>
          <p class="text-[var(--muted)] font-medium">Upload your photos and provide details for your personalized video.</p>
        </div>

        <div>
          <div class="how-number mx-auto mb-4"><span>3</span></div>
          <h4 class="text-lg font-bold mb-2">Receive Your Video</h4>
          <p class="text-[var(--muted)] font-medium">Get your professionally crafted video delivered within 24–48 hours.</p>
        </div>
      </div>
    </section>
  </main>
</div>

<!-- Footer -->
<footer class="py-12 sm:py-14 text-white text-center" style="background:#0F3D3E">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
  <h1 class="font-extrabold tracking-tight mb-4"
    style="color:var(--accent); font-size:clamp(1.8rem, 2.2vw + 1rem, 3rem) !important; line-height:1.1;">
  Tienda
</h1>
    <p class="text-white/85 font-medium">Creating beautiful, personalized videos that capture your special moments.</p>
  </div>
</footer>
@endsection
