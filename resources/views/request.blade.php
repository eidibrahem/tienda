@extends('layouts.app')

@section('title', 'Request Custom Video')

@section('content')
<style>
  /* Reset-ish */
  * {
    box-sizing: border-box;
  }

  /* AFTER */
  body {
    background: rgb(217, 179, 134);
  }

  /* beige page background */

  .video-preview {
    /** */
    background: linear-gradient(to bottom, #fef3e8 0%, #ffffff 100%);
    /* swap: use the old page bg here */
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* -------- ثابت الفيديو -------- */
  :root {
    --background: #fdfcf9;
    --video-fixed-w: 400px;
    --upload-icon-size: 28px;
    /* العرض الافتراضي للويب */
  }

  @media (max-width: 768px) {
    :root {
      --video-fixed-w: 320px;
      /* العرض في الموبايل */
    }
  }

  /* Layout */
  .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    background: #f7ebd5;
  }

  .header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
  }

  .content-wrapper {
    display: grid;
    grid-template-columns: 480px 1fr;
    gap: 40px;
    align-items: start;
  }

  @media (max-width: 1100px) {
    .content-wrapper {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 768px) {
    .form-row {
      grid-template-columns: 1fr !important;
    }
  }

  /* Back link + heading */
  .back-link {
    display: inline-flex;
    align-items: center;
    color: #666;
    text-decoration: none;
    font-size: 16px;
    transition: color .3s;
  }

  .back-link:hover {
    color: #333;
  }

  .back-link svg {
    margin-right: 8px;
  }

  .page-title {
    font-size: 28px;
    color: #1a1a1a;
    font-weight: 600;
  }

  /* Cards */
  .card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .1);
    overflow: hidden;
  }

  .card.pad {
    padding: 35px 40px;
  }

  /* Video preview */
  .video-preview {
    background: #fff;
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .video-fixed {
    width: var(--video-fixed-w);
    /* NEW */
  }

  .video-box {
    width: 100%;
    aspect-ratio: 9/16;
    border-radius: 8px;
    overflow: hidden;
    background: #000;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .video-box video,
  .video-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .play-button {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, .9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .play-button::after {
    content: "";
    width: 0;
    height: 0;
    border-left: 20px solid #333;
    border-top: 12px solid transparent;
    border-bottom: 12px solid transparent;
    margin-left: 4px;
  }

  .video-info {
    padding: 25px 30px;
  }

  .video-title {
    font-size: 22px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
  }

  .video-description {
    color: #666;
    font-size: 14px;
    margin-bottom: 12px;
  }

  .price-delivery {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .price {
    font-size: 32px;
    font-weight: 700;
    color: #ea8c55;
  }

  .delivery {
    color: #666;
    font-size: 14px;
  }

  /* Forms */
  .form-section h2 {
    font-size: 22px;
    margin-bottom: 30px;
    color: #1a1a1a;
    font-weight: 600;
  }

  .form-group {
    margin-bottom: 25px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
    font-weight: 500;
  }

  .required {
    color: #d93025;
  }

  input[type="text"],
  input[type="email"],
  textarea,
  select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
    transition: border-color .3s;
    background: #fff;
  }

  input:focus,
  textarea:focus,
  select:focus {
    outline: none;
    border-color: #4285f4;
  }

  textarea {
    min-height: 110px;
    resize: vertical;
  }

  input::placeholder,
  textarea::placeholder {
    color: #aaa;
  }

  /* Upload */

.upload-icon{
  width: var(--upload-icon-size);
  height: var(--upload-icon-size);
  margin: 0 auto 12px;
  opacity: .3;
}
  .upload-area {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 20px 20px;
    text-align: center;
    cursor: pointer;
    transition: .3s;
    background: #fafafa;
  }

  .upload-area:hover {
    border-color: #999;
    background: #f5f5f5;
  }

  .upload-area.dragover {
    border-color: #4285f4;
    background: #e8f0fe;
  }

  /* ===== Thumbnails under Upload Photos ===== */
  .file-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(92px, 1fr));
    gap: 10px;
    margin-top: 12px;
  }

  .file-thumb {
    position: relative;
    border: 1px solid #e6e6e6;
    border-radius: 10px;
    background: #fff;
    padding: 6px;
    box-shadow: 0 1px 6px rgba(0, 0, 0, .05);
  }

  .file-thumb img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border-radius: 8px;
    display: block;
  }

  .file-thumb .meta {
    margin-top: 6px;
    font-size: 12px;
    color: #555;
    line-height: 1.3;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    word-break: break-word;
  }

  .file-remove-btn {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 22px;
    height: 22px;
    border: none;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .12);
    color: #d93025;
    cursor: pointer;
    line-height: 22px;
    text-align: center;
    padding: 0;
  }

  /* ========================================== */

  /* Buttons */
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    font-weight: 600;
  }

  .btn-primary {
    background: #db732a;
    color: #fff;
    padding: 14px 32px;
    font-size: 16px;
    font-weight: 900;
    width: 100%;
  }

  .btn-primary:hover {
    background: #d97a43;
  }

  /* Billing/Payment (UI only; hook to your processor separately) */
  .section-subtitle {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 12px;
  }

  .billing-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
  }

  .billing-details {
    color: #333;
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 10px;
  }

  .billing-note {
    color: #666;
    font-size: 13px;
    margin-top: 10px;
  }

  .card-input-wrapper {
    position: relative;
  }

  .card-icons {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    gap: 6px;
  }

  .card-icons img {
    height: 20px;
    width: auto;
  }

  .cvc-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
  }

  .powered-by {
    text-align: center;
    color: #666;
    font-size: 13px;
    margin-top: 15px;
  }

  input[type="file"] {
    display: none;
  }

  .hidden {
    display: none !important;
  }

  /* معاينة صورة صغيرة داخل كارت المعلومات */
  .thumbs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 8px;
  }

  .thumbs img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #eee;
  }
</style>

<div class="container">
  <!-- Header -->
  <div class="header">
    <a href="{{ route('home') }}" class="back-link">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
        <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
      </svg>
      Back to Gallery
    </a>
    <h1 class="page-title">Request Custom Video</h1>
  </div>

  <div class="content-wrapper">
    <!-- LEFT: Template card -->
    <div class="card">
      <div class="video-preview">
        <div class="video-fixed">
          <div class="video-box">
            @if($template->video_url)
            <video controls playsinline poster="{{ $template->thumbnail_url ? asset($template->thumbnail_url) : '' }}">
              <source src="{{ asset($template->video_url) }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            @else
            <img src="{{ $template->thumbnail_url ? asset($template->thumbnail_url) : asset('/images/placeholder.jpg') }}" alt="{{ $template->name }}">
            <div class="play-button" aria-hidden="true"></div>
            @endif
          </div>
        </div>
      </div>

      <!-- Info card (hidden until form complete) -->
      <div id="infoCard" class="video-info hidden">
        <h3 class="video-title" id="infoTitle">{{ $template->name }}</h3>
        <p class="video-description" id="infoDesc">{{ $template->description }}</p>

        <ul style="margin: 0 0 10px 18px; color:#333; font-size:14px; line-height:1.7;">
          <li><b>Name:</b> <span id="sumName">—</span></li>
          <li><b>Email:</b> <span id="sumEmail">—</span></li>
          <li><b>Country:</b> <span id="sumCountry">—</span></li>
          <li><b>Your Notes:</b> <span id="sumUserDesc">—</span></li>
          <li><b>Photos:</b> <span id="sumFiles">0 file(s)</span></li>
        </ul>

        <div class="thumbs" id="sumThumbs"></div>

        <div class="price-delivery" style="margin-top:12px">
          <div class="price">{{ number_format($template->price, 0) }} AED</div>
          <div class="delivery">Delivery: {{ $template->delivery_time ?? '24-48 hours' }}</div>
        </div>
      </div>
    </div>

    <!-- RIGHT: Forms -->
    <div>
      <!-- Upload & details -->
      <div class="card pad" id="formCard">
        <h2>Upload Your Photos & Details</h2>

        <form id="videoRequestForm" action="{{ route('orders.store', $template) }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-group">
            <label>Upload Photos <span class="required">*</span></label>
            <div class="upload-area" id="uploadArea">
              <div class="upload-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 15s2.021-.015 5.253-4.218C11.245 7 14.952 7.011 18.7 11M12 3v12m0 0l-4-4m4 4l4-4" />
                  <path d="M2 17l.621 2.485A2 2 0 004.561 21h14.878a2 2 0 001.94-1.515L22 17" />
                </svg>
              </div>
              <p class="upload-text">
                Drag and drop your photos here, or <span class="browse" id="browseBtn">browse files</span>
              </p>
              <p class="upload-info">Supports: JPG, PNG, GIF (Max 10MB each)</p>
              <input type="file" id="fileInput" name="photos[]" multiple accept="image/jpeg,image/png,image/gif">
            </div>

            <!-- Thumbnails appear here -->
            <div class="file-list" id="fileList"></div>

            @error('photos') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            @error('photos.*') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Your Name <span class="required">*</span></label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
              @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
              <label>Email Address <span class="required">*</span></label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required>
              @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="form-group">
            <label>Country</label>
            <input type="text" id="country" name="country" value="{{ old('country') }}" placeholder="United Arab Emirates">
            @error('country') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="form-group">
            <label>Video Description <span class="required">*</span></label>
            <textarea id="description" name="description" required placeholder="Describe your video requirements, style preferences, and any specific instructions...">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            <p class="billing-note">The more details you provide, the better we can tailor your video.</p>
          </div>

        </form>
      </div>

      <!-- Payment (hidden initially) -->
      <div class="card pad hidden" id="paymentCard" style="margin-top:20px;">
        <h2>Payment Information</h2>

        <div class="billing-info">
          <h3 class="section-subtitle">Billing Information</h3>
          <p class="billing-details">
            <strong>Name:</strong> <span id="billingName">—</span><br>
            <strong>Email:</strong> <span id="billingEmail">—</span>
          </p>
          <p class="billing-note">This information will be used for your order and receipt.</p>
        </div>

        <form id="paymentForm">
          <div class="form-group">
            <label>Card number</label>
            <div class="card-input-wrapper">
              <input type="text" id="cardNumber" placeholder="1234 1234 1234 1234" maxlength="19" required>
              <div class="card-icons">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='20' viewBox='0 0 32 20'%3E%3Crect width='32' height='20' rx='2' fill='%231434CB'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' fill='white' font-family='Arial' font-size='8' font-weight='bold'%3EVISA%3C/text%3E%3C/svg%3E" alt="Visa">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='20' viewBox='0 0 32 20'%3E%3Crect width='32' height='20' rx='2' fill='%23EB001B'/%3E%3Ccircle cx='12' cy='10' r='6' fill='%23EB001B'/%3E%3Ccircle cx='20' cy='10' r='6' fill='%23FF5F00'/%3E%3C/svg%3E" alt="Mastercard">
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Expiry date</label>
              <input type="text" id="expiryDate" placeholder="MM / YY" maxlength="7" required>
            </div>
            <div class="form-group">
              <label>Security code</label>
              <div class="card-input-wrapper">
                <input type="text" id="securityCode" placeholder="CVC" maxlength="4" required>
                <div class="cvc-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" />
                    <line x1="1" y1="10" x2="23" y2="10" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Country</label>
            <select id="payCountry" required>
              <option value="AE" selected>United Arab Emirates</option>
              <option value="SA">Saudi Arabia</option>
              <option value="US">United States</option>
              <option value="GB">United Kingdom</option>
              <option value="IN">India</option>
              <option value="PK">Pakistan</option>
            </select>
          </div>

          <button class="btn btn-primary" type="submit">Create Order - {{ number_format($template->price, 0) }} AED</button>
          <div class="powered-by">Powered by <strong>Stripe</strong></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // --- Upload drag & drop (UI + validation) ---
  const uploadArea = document.getElementById('uploadArea');
  const fileInput = document.getElementById('fileInput');
  const browseBtn = document.getElementById('browseBtn');
  const fileList = document.getElementById('fileList');
  const thumbs = document.getElementById('sumThumbs');
  let selectedFiles = [];

  if (browseBtn) browseBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.click();
  });
  uploadArea.addEventListener('click', () => fileInput.click());
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
  });
  uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    handleFiles(e.dataTransfer.files);
  });
  fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

  function handleFiles(files) {
    const valid = ['image/jpeg', 'image/png', 'image/gif'];
    const max = 10 * 1024 * 1024;
    Array.from(files).forEach(file => {
      if (!valid.includes(file.type)) {
        alert(`${file.name} is not a valid image type`);
        return;
      }
      if (file.size > max) {
        alert(`${file.name} is too large. Max size is 10MB`);
        return;
      }
      const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified);
      if (exists) return; // avoid duplicates
      selectedFiles.push(file);
    });
    displayFiles();
  }

  function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    const kb = bytes / 1024;
    if (kb < 1024) return kb.toFixed(1) + ' KB';
    return (kb / 1024).toFixed(1) + ' MB';
  }

  // === Thumbnails in #fileList ===
  function displayFiles() {
    fileList.innerHTML = '';

    selectedFiles.forEach((file, i) => {
      const url = URL.createObjectURL(file);
      const item = document.createElement('div');
      item.className = 'file-thumb';
      item.innerHTML = `
        <img src="${url}" alt="preview">
        <div class="meta">${file.name}</div>
        <div class="meta">${formatSize(file.size)}</div>
        <button type="button" class="file-remove-btn" aria-label="Remove" title="Remove">&times;</button>
      `;

      // remove button
      item.querySelector('.file-remove-btn').addEventListener('click', () => {
        URL.revokeObjectURL(url);
        removeFile(i);
      });

      fileList.appendChild(item);
    });

     // Sync to input (Laravel will receive files)
     const dt = new DataTransfer();
     selectedFiles.forEach(f => dt.items.add(f));
     fileInput.files = dt.files;
     
     // Auto-check after file changes
     checkAndShowCards();
  }

  function removeFile(i) {
    selectedFiles.splice(i, 1);
    displayFiles();
    // Auto-check after file removal
    checkAndShowCards();
  }

   // --- Auto-validation: check fields and show info+payment automatically ---
   const infoCard = document.getElementById('infoCard');
   const paymentCard = document.getElementById('paymentCard');

   function checkAndShowCards() {
     const name = document.getElementById('name').value.trim();
     const email = document.getElementById('email').value.trim();
     const country = document.getElementById('country').value.trim();
     const desc = document.getElementById('description').value.trim();

     // Check if all required fields are filled and at least one photo is uploaded
     if (selectedFiles.length > 0 && name && email && desc) {
       // Fill summary card
       document.getElementById('sumName').textContent = name;
       document.getElementById('sumEmail').textContent = email;
       document.getElementById('sumCountry').textContent = country || '—';
       document.getElementById('sumUserDesc').textContent = desc;
       document.getElementById('sumFiles').textContent = `${selectedFiles.length} file(s)`;

       // Tiny thumbs for summary card
       thumbs.innerHTML = '';
       selectedFiles.slice(0, 6).forEach((file) => {
         const img = document.createElement('img');
         img.alt = 'photo';
         const reader = new FileReader();
         reader.onload = e => {
           img.src = e.target.result;
         };
         reader.readAsDataURL(file);
         thumbs.appendChild(img);
       });

       // Show cards
       infoCard.classList.remove('hidden');
       paymentCard.classList.remove('hidden');

       // Mirror to billing panel
       document.getElementById('billingName').textContent = name;
       document.getElementById('billingEmail').textContent = email;
     } else {
       // Hide cards if requirements not met
       infoCard.classList.add('hidden');
       paymentCard.classList.add('hidden');
     }
   }

   // Auto-check on every input change
   document.getElementById('name').addEventListener('input', checkAndShowCards);
   document.getElementById('email').addEventListener('input', checkAndShowCards);
   document.getElementById('country').addEventListener('input', checkAndShowCards);
   document.getElementById('description').addEventListener('input', checkAndShowCards);

  // --- Payment UI formatting + validation then submit main form ---
  document.getElementById('cardNumber').addEventListener('input', function(e) {
    let v = e.target.value.replace(/\s/g, '').replace(/\D/g, '').slice(0, 16);
    e.target.value = (v.match(/.{1,4}/g) || [v]).join(' ');
  });
  document.getElementById('expiryDate').addEventListener('input', function(e) {
    let v = e.target.value.replace(/\s|\/+/g, '').replace(/\D/g, '').slice(0, 4);
    if (v.length >= 3) {
      v = v.slice(0, 2) + ' / ' + v.slice(2);
    }
    e.target.value = v;
  });

   document.getElementById('paymentForm').addEventListener('submit', (e) => {
     e.preventDefault();

     // Show loading message
     const submitBtn = e.target.querySelector('button[type="submit"]');
     const originalText = submitBtn.textContent;
     submitBtn.textContent = 'Creating Order...';
     submitBtn.disabled = true;

     // Simple approach: submit the form directly and handle response
     const form = document.getElementById('videoRequestForm');
     
     // Create a hidden iframe to submit the form
     const iframe = document.createElement('iframe');
     iframe.style.display = 'none';
     iframe.name = 'formSubmit';
     document.body.appendChild(iframe);
     
     // Set form target to iframe
     form.target = 'formSubmit';
     
     // Handle iframe load (form submission complete)
     iframe.onload = function() {
       // Order submitted, show success message and redirect
       alert('Order created successfully! We will contact you soon.');
       window.location.href = '{{ route("home") }}';
     };
     
     // Submit the form
     form.submit();
   });
</script>
@endsection
