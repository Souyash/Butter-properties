<?php
/**
 * Butter Property - Dynamic Property Details (PHP Backend)
 * butterproperty.com
 */

require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/models/Property.php';
require_once __DIR__ . '/backend/models/Inquiry.php';

// Retrieve requested property
$requestedId = $_GET['id'] ?? '';
$requestedSlug = $_GET['slug'] ?? '';
$prop = null;

if (!empty($requestedId)) {
    $prop = Property::getById($requestedId);
} elseif (!empty($requestedSlug)) {
    $prop = Property::getBySlug($requestedSlug);
}

// Fallback to primary flagship property if not found
$notFound = false;
if ($prop === null) {
    $notFound = !empty($requestedId) || !empty($requestedSlug);
    $prop = Property::getById('dxb-01');
}

// Handle Direct Server-Side POST Submission (Progressive Enhancement)
$formSuccess = false;
$formError = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['submit_inquiry'])) {
    try {
        $lead = Inquiry::create([
            'source' => 'Property Dossier Form (PHP-SSR)',
            'name' => $_POST['client_name'] ?? '',
            'email' => $_POST['client_email'] ?? '',
            'phone' => $_POST['client_phone'] ?? '',
            'propertyId' => $prop['id'],
            'property' => $prop['title'],
            'region' => $prop['regionLabel'],
            'timeline' => $_POST['client_timeline'] ?? 'Immediate Allocation',
            'estimatedBudget' => $prop['formattedPriceAED'],
            'notes' => $_POST['client_notes'] ?? ''
        ]);
        $formSuccess = true;
    } catch (Throwable $e) {
        $formError = $e->getMessage();
    }
}

// Related Curated Allocations
$relatedProperties = Property::getRelated($prop['id'], 2);

// Dynamic SEO & OpenGraph Variables
$pageTitle = htmlspecialchars($prop['title']) . " | " . htmlspecialchars($prop['regionLabel']) . " | Butter Property";
$pageDesc = htmlspecialchars(mb_substr($prop['tagline'] . ' - ' . $prop['description'], 0, 160));
$pageUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/property.php');
$pageImage = htmlspecialchars($prop['mainImage']);
$refCode = htmlspecialchars($prop['refCode']);
$waMessage = rawurlencode("Hello Butter Property, I am interested in private due diligence for \"{$prop['title']}\" ({$prop['flag']} {$prop['regionLabel']}, REF: {$refCode}). Please share the confidential dossier.");
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <meta name="description" content="<?= $pageDesc ?>">
  <link rel="icon" type="image/png" href="./BUTTER-PROPERTY-LO-FF.png?v=2">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= $pageUrl ?>">
  <meta property="og:title" content="<?= $pageTitle ?>">
  <meta property="og:description" content="<?= $pageDesc ?>">
  <meta property="og:image" content="<?= $pageImage ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $pageTitle ?>">
  <meta name="twitter:description" content="<?= $pageDesc ?>">
  <meta name="twitter:image" content="<?= $pageImage ?>">

  <!-- Schema.org Real Estate Listing Structured Data for Google -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "RealEstateListing",
    "name": "<?= addslashes($prop['title']) ?>",
    "description": "<?= addslashes($prop['tagline']) ?>",
    "url": "<?= $pageUrl ?>",
    "image": "<?= $prop['mainImage'] ?>",
    "price": "<?= $prop['priceAED'] ?>",
    "priceCurrency": "AED",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "<?= addslashes($prop['city']) ?>",
      "addressCountry": "<?= addslashes($prop['country']) ?>"
    },
    "numberOfRooms": <?= (int)$prop['bedrooms'] ?>,
    "floorSize": {
      "@type": "QuantitativeValue",
      "value": <?= (int)$prop['sizeSqFt'] ?>,
      "unitCode": "FTK"
    }
  }
  </script>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            butter: {
              yellow: '#FDDB00',
              'yellow-hover': '#F5D000',
              'yellow-light': '#FFF066',
              'yellow-warm': '#F5C800',
              'yellow-soft': '#FFFDE6',
              'yellow-border': '#FAE875',
              'yellow-dark': '#8C6D00',
              amber: '#B88E00',
              charcoal: '#171510',
              muted: '#78716C',
            }
          },
          fontFamily: {
            custom: ['"Butter Montserrat"', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
            serif: ['"Playfair Display"', 'Georgia', 'serif'],
          }
        }
      }
    }
  </script>

  <!-- Google Fonts & Font Awesome Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @font-face {
      font-family: 'Butter Montserrat';
      src: url('./FONT/Montserrat-Medium.ttf') format('truetype');
      font-weight: 400 500;
      font-style: normal;
      font-display: swap;
    }
    @font-face {
      font-family: 'Butter Montserrat';
      src: url('./FONT/Montserrat-Bold.ttf') format('truetype');
      font-weight: 600 700 800;
      font-style: normal;
      font-display: swap;
    }

    body {
      font-family: 'Butter Montserrat', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: #FAFAF9;
      color: #1C1917;
      -webkit-font-smoothing: antialiased;
    }

    .font-serif-luxury {
      font-family: 'Playfair Display', Georgia, serif;
    }

    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #FAFAF9;
    }
    ::-webkit-scrollbar-thumb {
      background: #D6D3D1;
      border-radius: 3px;
    }

    .glass-nav {
      background: rgba(250, 250, 249, 0.92);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }

    .gallery-thumb.active {
      outline: 2px solid #FDDB00;
      opacity: 1;
    }

    /* Interactive Zoom Lens Frame */
    .gallery-display-frame {
      position: relative;
      overflow: hidden;
      cursor: zoom-in;
    }
    .gallery-display-frame.is-zoomed {
      cursor: zoom-out;
    }
    .gallery-display-frame img.zoom-active {
      transform: scale(2.2);
      transition: transform 0.08s ease-out;
      transform-origin: center center;
    }
    .gallery-display-frame img.zoom-idle {
      transform: scale(1);
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease-out;
    }
  </style>
</head>
<body class="bg-[#FAFAF9] text-stone-900 selection:bg-stone-200 selection:text-stone-900 min-h-screen flex flex-col antialiased">

  <!-- ==================== 1. TOP TICKER ==================== -->
  <aside aria-label="Announcement" class="border-b border-purple-900/40 bg-[#1D0838] text-purple-200/90 text-xs py-2 px-4 text-center tracking-wider uppercase font-medium flex items-center justify-center gap-3">
    <span class="inline-flex items-center gap-1.5 text-[#FDDB00]">
      <span class="w-2 h-2 rounded-full bg-[#FDDB00] shadow-sm animate-pulse"></span>
      <span class="font-bold text-white">Private Client Desk Active</span>
    </span>
    <span class="hidden sm:inline text-purple-700">•</span>
    <span class="hidden sm:inline text-purple-200">Direct Principal Representation • Zero Broker Noise</span>
    <span class="text-purple-700">•</span>
    <a href="index.html#contact" class="text-[#FDDB00] hover:text-[#FFF066] hover:underline underline-offset-4 font-bold tracking-widest text-[11px] transition-colors">Enquire Privately &rarr;</a>
  </aside>

  <!-- ==================== 2. STICKY HEADER ==================== -->
  <header class="sticky top-0 z-40 w-full glass-nav border-b border-stone-200/80 shadow-xs transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        
        <!-- Brand Logo -->
        <a href="index.html" class="flex items-center gap-3 group focus:outline-none">
          <div class="relative flex items-center justify-center py-1">
            <img 
              src="./BUTTER-PROPERTY-LO-FF.png?v=2" 
              alt="Butter Property" 
              class="h-14 sm:h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105" style="height: 56px; max-height: 64px; width: auto;"
            />
          </div>
          <span class="sr-only">Butter Property</span>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide text-stone-600">
          <a href="index.html#properties" class="hover:text-stone-950 transition-colors py-1 flex items-center gap-1.5 font-medium">
            <i class="fa-solid fa-arrow-left text-xs text-[#7323DC]"></i>
            <span>All Properties</span>
          </a>
          <a href="dubai-portfolio.html" class="hover:text-stone-950 transition-colors py-1">
            Dubai Portfolio <span class="text-[10px] uppercase tracking-widest px-1.5 py-0.5 ml-1 bg-[#F4EDFD] text-[#7323DC] font-bold rounded border border-[#E4D2FA]">UAE</span>
          </a>
          <a href="india-portfolio.html" class="hover:text-stone-950 transition-colors py-1">
            India Portfolio <span class="text-[10px] uppercase tracking-widest px-1.5 py-0.5 ml-1 bg-[#FFFDE6] text-[#8C6D00] font-bold rounded border border-[#FAE875]">IND</span>
          </a>
          <a href="index.html#why-butter" class="hover:text-stone-950 transition-colors py-1">
            Why Butter
          </a>
          <a href="#contact-advisor" class="hover:text-stone-950 transition-colors py-1">
            Advisory Desk
          </a>
        </nav>

        <!-- Right Side: Currency & Consultation Action -->
        <div class="hidden sm:flex items-center space-x-4">
          <!-- Currency Toggle Button -->
          <div class="flex items-center border border-stone-200 rounded-full p-1 bg-white text-xs font-semibold shadow-xs">
            <button 
              id="detailCurrencyBtnINR" 
              onclick="setDetailCurrency('INR')" 
              class="px-2.5 py-1 rounded-full transition-all text-stone-500 hover:text-stone-900"
              title="View price in Indian Rupees"
            >
              ₹ INR
            </button>
            <button 
              id="detailCurrencyBtnAED" 
              onclick="setDetailCurrency('AED')" 
              class="px-2.5 py-1 rounded-full bg-[#7323DC] text-white font-bold transition-all shadow-xs"
              title="View price in UAE Dirhams"
            >
              AED
            </button>
          </div>

          <!-- Primary Modal Trigger -->
          <button 
            onclick="openPropertyInquiryModal()" 
            class="inline-flex items-center justify-center px-5 py-2.5 border border-[#E5C700] text-xs font-bold uppercase tracking-widest text-stone-950 bg-[#FDDB00] hover:bg-[#FFE524] transition-all rounded-sm shadow-sm"
          >
            Private Consultation
          </button>
        </div>

        <!-- Mobile Quick Return -->
        <div class="flex md:hidden items-center space-x-3">
          <button 
            onclick="toggleDetailCurrency()" 
            id="mobileCurrBadge" 
            class="text-[11px] font-semibold border border-stone-300 px-2 py-1 rounded bg-white text-stone-700"
          >
            AED
          </button>
          <a href="index.html#properties" class="text-xs font-semibold text-stone-800 border border-stone-200 px-3 py-1.5 rounded bg-white">
            &larr; Portfolio
          </a>
        </div>

      </div>
    </div>
  </header>

  <!-- ==================== 3. BREADCRUMBS & TOP BAR ==================== -->
  <div class="border-b border-stone-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3.5 flex items-center justify-between gap-2 text-xs text-stone-500">
      
      <div class="flex items-center space-x-1.5 sm:space-x-2 text-[11px] sm:text-xs truncate">
        <a href="index.html" class="hover:text-stone-900 hidden sm:inline">Home</a>
        <span class="hidden sm:inline">/</span>
        <a href="index.html#properties" class="hover:text-stone-900 flex items-center gap-1 font-medium text-stone-700">
          <i class="fa-solid fa-arrow-left text-[10px] text-[#7323DC] sm:hidden"></i>
          <span>Portfolio</span>
        </a>
        <span>/</span>
        <span class="text-stone-400 hidden sm:inline"><?= htmlspecialchars($prop['regionLabel']) ?></span>
        <span class="hidden sm:inline">/</span>
        <span class="text-stone-900 font-semibold truncate max-w-[170px] sm:max-w-xs"><?= htmlspecialchars($prop['title']) ?></span>
      </div>

      <div class="flex items-center gap-2 sm:gap-4 text-stone-500 shrink-0">
        <span class="px-2 sm:px-2.5 py-0.5 bg-stone-100 text-stone-800 font-semibold rounded text-[10px] sm:text-[11px] uppercase tracking-wider">
          <?= htmlspecialchars($prop['badge']) ?>
        </span>
        <span class="hidden sm:inline text-stone-300">|</span>
        <span class="hidden sm:inline"><?= $refCode ?></span>
      </div>

    </div>
  </div>

  <?php if ($notFound): ?>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
    <div class="p-3 bg-amber-50 border border-amber-200 rounded text-xs text-amber-800 flex items-center justify-between">
      <span>Notice: The requested allocation ID was not located. Displaying flagship trophy property <strong><?= htmlspecialchars($prop['title']) ?></strong>.</span>
      <a href="index.html#properties" class="font-bold underline ml-2">Browse All &rarr;</a>
    </div>
  </div>
  <?php endif; ?>

  <main class="flex-grow">
    
    <!-- ==================== 4. PROPERTY HERO & IMMERSIVE GALLERY ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 sm:pt-8 pb-10 sm:pb-12">
      
      <!-- Title & Location Header: Sleek & responsive -->
      <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 sm:gap-6 mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-stone-200">
        <div class="space-y-1.5 sm:space-y-2">
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-stone-600 bg-stone-100 px-2.5 py-1 rounded">
              <span><?= htmlspecialchars($prop['flag']) ?></span>
              <span><?= htmlspecialchars($prop['regionLabel']) ?></span>
            </span>
            <span class="text-stone-300">•</span>
            <span class="text-xs font-semibold text-stone-600"><?= htmlspecialchars($prop['type']) ?></span>
            <span class="text-stone-300">•</span>
            <span class="text-xs text-stone-500 font-medium"><?= htmlspecialchars($prop['ownership']) ?></span>
          </div>
          
          <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-stone-950 font-serif-luxury">
            <?= htmlspecialchars($prop['title']) ?>
          </h1>
          
          <p class="text-xs sm:text-sm text-stone-600 max-w-3xl leading-relaxed">
            <?= htmlspecialchars($prop['tagline']) ?>
          </p>
        </div>

        <!-- Pricing Card Summary in Header -->
        <div class="flex flex-row lg:flex-col items-baseline lg:items-end justify-between lg:justify-center border-t lg:border-t-0 pt-3 lg:pt-0 border-stone-200 shrink-0">
          <span class="text-[10px] sm:text-xs uppercase font-semibold text-stone-500 tracking-wider">Acquisition Value</span>
          <div class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-950 font-serif-luxury" id="displayHeaderPrice">
            <?= $prop['formattedPriceAED'] ?>
          </div>
          <span class="text-[11px] font-semibold text-[#8C6D00] flex items-center gap-1 bg-[#FFFDE6] px-2 py-0.5 rounded border border-[#FAE875] mt-1">
            <i class="fa-solid fa-chart-line text-[10px]"></i>
            <span><?= htmlspecialchars($prop['rentalYield']) ?></span>
          </span>
        </div>
      </div>

      <!-- Main Showcase Grid: Left Large Photo with Zoom + Right Key Metrics Card -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
        
        <!-- Interactive Gallery & Zoom Section (Left 8 Cols) -->
        <div class="lg:col-span-8 space-y-4">
          
          <!-- Large Main Interactive Viewport -->
          <div class="relative rounded-lg overflow-hidden border border-stone-200 bg-stone-950 shadow-sm">
            <div 
              id="galleryMainViewport" 
              class="gallery-display-frame aspect-[16/10] sm:aspect-[16/9] w-full flex items-center justify-center relative select-none"
              title="Click or Hover to zoom into architectural finishes"
            >
              <img 
                id="mainActiveImage" 
                src="<?= htmlspecialchars($prop['mainImage']) ?>" 
                alt="<?= htmlspecialchars($prop['title']) ?>" 
                class="w-full h-full object-cover zoom-idle pointer-events-none"
                loading="eager"
              />

              <!-- Zoom Instruction Hint Badge -->
              <div id="zoomHintBadge" class="absolute top-4 left-4 z-10 bg-stone-900/80 text-white text-[11px] font-medium px-3 py-1.5 rounded-full backdrop-blur-md flex items-center gap-2 pointer-events-none transition-opacity duration-300">
                <i class="fa-solid fa-magnifying-glass-plus text-[#FDDB00]"></i>
                <span>Hover or Tap to Inspect Architectural Finish</span>
              </div>

              <!-- Fullscreen Expand Button -->
              <button 
                type="button" 
                onclick="openFullscreenLightbox()" 
                class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-stone-900/80 text-white hover:text-[#FDDB00] hover:bg-stone-900 transition-colors flex items-center justify-center backdrop-blur-md shadow-sm"
                title="Expand Fullscreen Gallery"
              >
                <i class="fa-solid fa-expand text-xs"></i>
              </button>

              <!-- Image Index Pill -->
              <div class="absolute bottom-4 right-4 z-10 bg-stone-900/80 text-white text-[11px] font-semibold px-2.5 py-1 rounded backdrop-blur-md">
                <span id="currentImgIndex">1</span> / <span id="totalImgCount"><?= count($prop['gallery'] ?? []) ?: 1 ?></span>
              </div>
            </div>

            <!-- Active Slide Caption -->
            <div class="p-3 sm:p-4 bg-white border-t border-stone-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
              <div>
                <h4 id="activeImgTitle" class="text-xs sm:text-sm font-semibold text-stone-900">
                  <?= htmlspecialchars($prop['gallery'][0]['title'] ?? $prop['title']) ?>
                </h4>
                <p id="activeImgCaption" class="text-[11px] sm:text-xs text-stone-500">
                  <?= htmlspecialchars($prop['gallery'][0]['caption'] ?? $prop['tagline']) ?>
                </p>
              </div>
              <span class="text-[10px] text-stone-400 uppercase tracking-widest shrink-0 font-medium">Verified Architectural Photography</span>
            </div>
          </div>

          <!-- Thumbnail Strip -->
          <div class="grid grid-cols-5 gap-2 sm:gap-3" id="galleryThumbnails">
            <?php foreach (($prop['gallery'] ?? []) as $idx => $g): ?>
            <button 
              type="button" 
              onclick="selectGalleryImage(<?= $idx ?>)"
              class="gallery-thumb relative rounded overflow-hidden aspect-[16/10] bg-stone-100 border border-stone-200 transition-all <?= $idx === 0 ? 'ring-2 ring-[#FDDB00] opacity-100' : 'opacity-60 hover:opacity-100' ?>"
              title="<?= htmlspecialchars($g['title']) ?>"
            >
              <img src="<?= htmlspecialchars($g['url']) ?>" alt="<?= htmlspecialchars($g['title']) ?>" class="w-full h-full object-cover pointer-events-none" />
            </button>
            <?php endforeach; ?>
          </div>

        </div>

        <!-- Key Metrics & Overview Sidebar (Right 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
          
          <!-- Key Asset Specification Card -->
          <div class="bg-white border border-stone-200 rounded-lg p-5 sm:p-6 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
              <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Asset Specifications</h3>
              <span class="text-xs font-bold text-[#7323DC]"><?= $refCode ?></span>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold uppercase text-stone-400">Total Built-Up Area</span>
                <div class="text-base font-bold text-stone-900">
                  <span id="metricSize"><?= number_format($prop['sizeSqFt']) ?></span> <span class="text-xs font-normal text-stone-500">sq.ft</span>
                </div>
              </div>

              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold uppercase text-stone-400">Layout Bedrooms</span>
                <div class="text-base font-bold text-stone-900" id="metricBeds">
                  <?= (int)$prop['bedrooms'] ?> Bed
                </div>
              </div>

              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold uppercase text-stone-400">En-Suite Baths</span>
                <div class="text-base font-bold text-stone-900" id="metricBaths">
                  <?= (int)$prop['bathrooms'] ?> Bath
                </div>
              </div>

              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold uppercase text-stone-400">Handover / Completion</span>
                <div class="text-xs font-bold text-stone-900 truncate" id="metricCompletion">
                  <?= htmlspecialchars(explode('(', $prop['completion'])[0] ?? $prop['completion']) ?>
                </div>
              </div>

              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold uppercase text-stone-400">Title Framework</span>
                <div class="text-xs font-bold text-stone-900 truncate" id="metricOwnership">
                  <?= htmlspecialchars(explode('(', $prop['ownership'])[0] ?? $prop['ownership']) ?>
                </div>
              </div>

              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold uppercase text-stone-400">Residency Eligibility</span>
                <div class="text-xs font-bold text-emerald-700 flex items-center gap-1" id="metricVisa">
                  <i class="fa-solid fa-passport text-[10px]"></i>
                  <span><?= htmlspecialchars(explode(' ', $prop['visaEligible'])[0] ?? 'Golden') ?> Visa</span>
                </div>
              </div>
            </div>

            <!-- Developer & Service Charge Row -->
            <div class="pt-4 border-t border-stone-100 space-y-2 text-xs">
              <div class="flex justify-between items-center text-stone-600">
                <span class="text-stone-500">Lead Master Developer:</span>
                <span class="font-semibold text-stone-900"><?= htmlspecialchars($prop['developer']) ?></span>
              </div>
              <div class="flex justify-between items-center text-stone-600">
                <span class="text-stone-500">Service Charges / Maintenance:</span>
                <span class="font-semibold text-stone-900"><?= htmlspecialchars($prop['serviceCharges']) ?></span>
              </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="pt-2 space-y-2.5">
              <a 
                href="#contact-advisor" 
                class="w-full py-3 bg-[#7323DC] hover:bg-[#5E1BB8] text-white text-xs font-bold uppercase tracking-wider rounded text-center block transition-all shadow-sm"
              >
                Request Confidential Due-Diligence Pack
              </a>
              <a 
                href="https://wa.me/<?= BP_WHATSAPP_PHONE ?>?text=<?= $waMessage ?>"
                target="_blank" 
                rel="noopener noreferrer"
                class="w-full py-2.5 border border-emerald-600 text-emerald-700 hover:bg-emerald-50 text-xs font-bold uppercase tracking-wider rounded flex items-center justify-center gap-2 transition-all"
              >
                <i class="fa-brands fa-whatsapp text-sm text-[#25D366]"></i>
                <span>Direct WhatsApp Consultation</span>
              </a>
            </div>

            <div class="text-[11px] text-stone-400 text-center leading-tight">
              Strict Buy-Side Fiduciary Advisory • 100% Direct Developer Title
            </div>
          </div>

          <!-- Trust & Due Diligence Guarantee Card -->
          <div class="p-4 bg-stone-100 rounded-lg border border-stone-200 text-xs space-y-2">
            <div class="font-bold text-stone-900 flex items-center gap-1.5">
              <i class="fa-solid fa-shield-halved text-[#7323DC]"></i>
              <span>Butter Property Advisory Standard</span>
            </div>
            <p class="text-stone-600 leading-relaxed text-[11px]">
              Every listed asset has undergone title deed cross-checking, developer escrow solvency verification, and comprehensive legal audit.
            </p>
          </div>

        </div>

      </div>

    </section>

    <!-- ==================== 5. IN-DEPTH ARCHITECTURAL NARRATIVE & HIGHLIGHTS ==================== -->
    <section class="border-t border-stone-200 bg-white py-14 sm:py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
          
          <!-- Left: Narrative, Highlights, Amenities, Financials (8 cols) -->
          <div class="lg:col-span-8 space-y-12">
            
            <!-- Architectural Description -->
            <div class="space-y-4">
              <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-[#7323DC]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FDDB00]"></span>
                <span>The Residence</span>
              </div>
              <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-950 font-serif-luxury">
                Architectural Narrative &amp; Vision
              </h2>
              <div class="prose prose-stone text-stone-600 text-sm sm:text-base leading-relaxed space-y-4" id="propDescription">
                <?php 
                $paras = explode("\n\n", $prop['description']);
                foreach ($paras as $p) {
                    echo "<p>" . htmlspecialchars(trim($p)) . "</p>";
                }
                ?>
              </div>
            </div>

            <!-- Signature Highlights Grid -->
            <div class="space-y-5 pt-8 border-t border-stone-100">
              <h3 class="text-lg font-bold text-stone-950 tracking-tight">
                Signature Asset Highlights
              </h3>
              <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="propHighlights">
                <?php foreach (($prop['highlights'] ?? []) as $h): ?>
                <li class="flex items-start gap-2.5 p-3 rounded bg-stone-50 border border-stone-100 text-xs sm:text-sm text-stone-700">
                  <span class="text-[#FDDB00] mt-0.5 font-bold">✦</span>
                  <span class="leading-relaxed"><?= htmlspecialchars($h) ?></span>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>

            <!-- Ultra-Luxury Amenities -->
            <div class="space-y-5 pt-8 border-t border-stone-100">
              <h3 class="text-lg font-bold text-stone-950 tracking-tight">
                Curated Amenities &amp; Services
              </h3>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5" id="propAmenities">
                <?php foreach (($prop['amenities'] ?? []) as $a): ?>
                <div class="flex items-center gap-3 p-3.5 rounded border border-stone-200 bg-white">
                  <div class="w-8 h-8 rounded bg-[#F4EDFD] text-stone-800 flex items-center justify-center text-xs shrink-0 border border-[#E4D2FA]">
                    <i class="fa-solid <?= htmlspecialchars($a['icon']) ?> text-[#7323DC]"></i>
                  </div>
                  <div class="text-xs font-semibold text-stone-900"><?= htmlspecialchars($a['name']) ?></div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Financial Modeling & Yield Framework -->
            <div class="space-y-5 pt-8 border-t border-stone-100">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-stone-950 tracking-tight">
                  Financial Projections &amp; Tax Framework
                </h3>
                <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Independent Advisory Estimate</span>
              </div>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-1">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Projected Gross Rental Yield</span>
                  <div class="text-xl font-bold text-stone-950" id="finRental"><?= htmlspecialchars($prop['financials']['annualRentalValueAED'] ?? 'N/A') ?></div>
                  <p class="text-xs text-stone-500">Based on prime luxury tenant demand &amp; long-term lease covenants.</p>
                </div>

                <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-1">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Projected Net Cap Rate</span>
                  <div class="text-xl font-bold text-[#8C6D00]" id="finCapRate"><?= htmlspecialchars($prop['financials']['projectedCapRate'] ?? 'N/A') ?></div>
                  <p class="text-xs text-stone-500">Net of service charges, insurance, and asset management retainers.</p>
                </div>

                <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-1">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Capital Appreciation Outlook</span>
                  <div class="text-xl font-bold text-stone-950" id="finAppreciation"><?= htmlspecialchars($prop['financials']['capitalAppreciationForecast'] ?? 'N/A') ?></div>
                  <p class="text-xs text-stone-500">Supported by ultra-prime supply constraints in the immediate precinct.</p>
                </div>

                <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-1">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Tax Implications</span>
                  <div class="text-xl font-bold text-emerald-800" id="finTax"><?= htmlspecialchars($prop['financials']['taxImplications'] ?? 'N/A') ?></div>
                  <p class="text-xs text-stone-500">Repatriation-friendly structure with 0% personal tax exposure.</p>
                </div>
              </div>
            </div>

          </div>

          <!-- Right: Confidential Due Diligence Request Form (4 cols) -->
          <div class="lg:col-span-4" id="contact-advisor">
            
            <div class="sticky top-28 bg-[#FAFAF9] border border-stone-300 rounded-lg p-6 sm:p-7 shadow-sm space-y-5">
              
              <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#7323DC]">Confidential Brief</span>
                <h3 class="text-xl font-bold text-stone-950 font-serif-luxury">
                  Request Due-Diligence Dossier
                </h3>
                <p class="text-xs text-stone-600 leading-relaxed">
                  Receive verified architectural blueprints, payment milestones, escrow balances, and private title deeds.
                </p>
              </div>

              <?php if ($formSuccess): ?>
              <div class="p-4 bg-emerald-50 border border-emerald-300 rounded text-xs text-emerald-800 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-emerald-900">
                  <i class="fa-solid fa-check-circle text-emerald-600"></i>
                  <span>Dossier Brief Received</span>
                </div>
                <p>Our Senior Principal will dispatch the confidential due-diligence pack for <?= htmlspecialchars($prop['title']) ?> within 4 hours.</p>
              </div>
              <?php endif; ?>

              <?php if (!empty($formError)): ?>
              <div class="p-3 bg-red-50 border border-red-300 rounded text-xs text-red-800">
                <?= htmlspecialchars($formError) ?>
              </div>
              <?php endif; ?>

              <!-- Form with PHP Backend POST & AJAX Support -->
              <form 
                action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/property.php') ?>" 
                method="POST" 
                onsubmit="handleDetailInquiry(event)" 
                class="space-y-4"
              >
                <input type="hidden" name="property_id" value="<?= htmlspecialchars($prop['id']) ?>">
                <input type="hidden" name="submit_inquiry" value="1">

                <div>
                  <label class="block text-[11px] font-semibold uppercase text-stone-600 mb-1" for="detailClientName">
                    Principal or Legal Representative Name <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    id="detailClientName" 
                    name="client_name"
                    required 
                    placeholder="e.g. Rahul Singhania / Family Office" 
                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-stone-300 rounded focus:border-[#7323DC] focus:ring-1 focus:ring-[#7323DC] outline-none transition-colors"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-semibold uppercase text-stone-600 mb-1" for="detailClientEmail">
                    Confidential Email <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="email" 
                    id="detailClientEmail" 
                    name="client_email"
                    required 
                    placeholder="name@familyoffice.com" 
                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-stone-300 rounded focus:border-[#7323DC] focus:ring-1 focus:ring-[#7323DC] outline-none transition-colors"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-semibold uppercase text-stone-600 mb-1" for="detailClientPhone">
                    Direct Phone / WhatsApp (Optional)
                  </label>
                  <input 
                    type="tel" 
                    id="detailClientPhone" 
                    name="client_phone"
                    placeholder="+971 50 000 0000 / +91 98..." 
                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-stone-300 rounded focus:border-[#7323DC] focus:ring-1 focus:ring-[#7323DC] outline-none transition-colors"
                  />
                </div>

                <div>
                  <label class="block text-[11px] font-semibold uppercase text-stone-600 mb-1" for="detailClientTimeline">
                    Capital Deployment Timeline
                  </label>
                  <select 
                    id="detailClientTimeline" 
                    name="client_timeline"
                    class="w-full px-3.5 py-2.5 text-xs bg-white border border-stone-300 rounded focus:border-[#7323DC] focus:ring-1 focus:ring-[#7323DC] outline-none transition-colors text-stone-700"
                  >
                    <option value="Immediate Allocation (30 Days)">Immediate Allocation (30 Days)</option>
                    <option value="Current Quarter (Q1-Q2 2026)">Current Quarter (Q1-Q2 2026)</option>
                    <option value="1031 / Portfolio Relocation">Portfolio Relocation / Tax Transition</option>
                    <option value="Institutional Due Diligence">Institutional Due Diligence</option>
                  </select>
                </div>

                <div>
                  <label class="block text-[11px] font-semibold uppercase text-stone-600 mb-1" for="detailClientNotes">
                    Specific Diligence Requirements
                  </label>
                  <textarea 
                    id="detailClientNotes" 
                    name="client_notes"
                    rows="3" 
                    placeholder="e.g. Please include floor plans for level 48, title deeds, and developer payment schedule." 
                    class="w-full px-3.5 py-2 text-xs bg-white border border-stone-300 rounded focus:border-[#7323DC] focus:ring-1 focus:ring-[#7323DC] outline-none transition-colors resize-none"
                  ></textarea>
                </div>

                <button 
                  type="submit" 
                  class="w-full py-3.5 bg-[#FDDB00] hover:bg-[#FFE524] text-stone-950 font-bold text-xs uppercase tracking-widest rounded shadow-sm transition-all"
                >
                  Dispatch Private Dossier &rarr;
                </button>

                <p class="text-[10px] text-stone-400 text-center leading-relaxed">
                  Protected under mutual Non-Disclosure. Butter Property operates without broker spam.
                </p>
              </form>

              <!-- Direct Desk Access -->
              <div class="pt-6 border-t border-stone-200 text-xs text-stone-500 space-y-2">
                <div class="font-semibold text-stone-800 uppercase tracking-wider text-[10px]">Senior Partner In Charge:</div>
                <div class="text-stone-700 font-medium">Boutique Buy-Side Advisory Desk</div>
                <div class="flex items-center gap-2 text-stone-500">
                  <i class="fa-solid fa-envelope text-[#7323DC]"></i>
                  <a href="mailto:advisory@butterproperty.com" class="hover:underline">advisory@butterproperty.com</a>
                </div>
                <div class="flex items-center gap-2 text-stone-500">
                  <i class="fa-solid fa-phone text-[#7323DC]"></i>
                  <span>+971 4 800 BUTTER / +91 22 6100 8000</span>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- ==================== 6. RELATED CURATED ALLOCATIONS ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-stone-200">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
          <span class="text-xs font-semibold uppercase tracking-widest text-[#7323DC]">Portfolio Context</span>
          <h2 class="text-2xl font-semibold text-stone-950 tracking-tight">Other Curated Allocations</h2>
        </div>
        <a href="index.html#properties" class="text-xs font-semibold uppercase tracking-wider text-stone-900 hover:underline">
          View Complete Portfolio &rarr;
        </a>
      </div>

      <div id="relatedPropertiesGrid" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php foreach ($relatedProperties as $rel): ?>
        <div class="bg-white border border-stone-200 rounded-md overflow-hidden flex flex-col justify-between p-4 sm:p-5 group hover:shadow-md transition-shadow">
          <div class="flex gap-4">
            <img src="<?= htmlspecialchars($rel['mainImage']) ?>" alt="<?= htmlspecialchars($rel['title']) ?>" class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded shrink-0" />
            <div class="space-y-1">
              <span class="text-[10px] uppercase font-bold text-[#7323DC]"><?= htmlspecialchars($rel['flag']) ?> <?= htmlspecialchars($rel['regionLabel']) ?></span>
              <h4 class="text-sm font-semibold text-stone-900 group-hover:text-stone-700"><?= htmlspecialchars($rel['title']) ?></h4>
              <p class="text-xs text-stone-500"><?= htmlspecialchars($rel['neighborhood']) ?></p>
              <div class="text-xs font-bold text-stone-900 pt-1">
                <?= $rel['formattedPriceAED'] ?>
              </div>
            </div>
          </div>
          <div class="mt-4 pt-3 border-t border-stone-100 flex justify-end">
            <a href="property.php?id=<?= urlencode($rel['id']) ?>" class="text-xs font-bold text-[#7323DC] uppercase tracking-wider hover:underline flex items-center gap-1">
              <span>View Allocation</span>
              <span class="text-[#FDDB00]">&rarr;</span>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ==================== CONNECT THROUGH WHATSAPP ==================== -->
    <section class="bg-[#121A16] border-t border-emerald-900/60 py-12 sm:py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-[#173024] via-[#10231B] to-[#0A1711] border border-emerald-500/30 rounded-xl p-6 sm:p-10 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
          
          <div class="space-y-2 text-center md:text-left relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-semibold uppercase tracking-wider">
              <i class="fa-brands fa-whatsapp text-sm text-[#25D366]"></i>
              <span>Instant Private Advisory Desk</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
              Connect With Our Advisory Desk on WhatsApp
            </h3>
            <p class="text-stone-300 text-xs sm:text-sm max-w-xl leading-relaxed">
              Have immediate questions regarding this allocation, escrow verification, or Golden Visa eligibility? Chat directly with our senior managing partners in real-time.
            </p>
          </div>

          <a 
            id="whatsappPropertyBtn"
            href="https://wa.me/<?= BP_WHATSAPP_PHONE ?>?text=<?= $waMessage ?>" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-2.5 px-7 py-4 bg-[#25D366] hover:bg-[#20ba59] text-stone-950 font-bold text-xs sm:text-sm uppercase tracking-wider rounded-md shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 shrink-0 z-10"
          >
            <i class="fa-brands fa-whatsapp text-xl"></i>
            <span>Connect Through WhatsApp</span>
          </a>

        </div>
      </div>
    </section>

  </main>

  <!-- ==================== 7. FOOTER ==================== -->
  <footer class="bg-stone-950 text-stone-400 border-t border-stone-800 text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
        <div class="space-y-4">
          <img 
            src="./BUTTER-PROPERTY-LO-FF.png?v=2" 
            alt="Butter Property" 
            class="h-10 w-auto object-contain brightness-0 invert opacity-90"
          />
          <p class="text-xs text-stone-400 leading-relaxed">
            Butter Property is a boutique luxury real estate consultancy serving high-net-worth investors across India and Dubai.
          </p>
        </div>
        <div>
          <h4 class="text-xs font-semibold text-stone-200 uppercase tracking-widest mb-3">Dubai Desk</h4>
          <p class="text-xs text-stone-400 leading-relaxed">
            Downtown Dubai &amp; DIFC Gate District<br>
            dubai@butterproperty.com<br>
            +971 4 800 BUTTER
          </p>
        </div>
        <div>
          <h4 class="text-xs font-semibold text-stone-200 uppercase tracking-widest mb-3">India Desk</h4>
          <p class="text-xs text-stone-400 leading-relaxed">
            Worli Sea Face, Mumbai 400018<br>
            india@butterproperty.com<br>
            +91 22 6100 8000
          </p>
        </div>
        <div>
          <h4 class="text-xs font-semibold text-stone-200 uppercase tracking-widest mb-3">Legal &amp; Title</h4>
          <p class="text-xs text-stone-400 leading-relaxed">
            Strict buy-side representation. Verified RERA registration, DED commercial licenses, and title escrow management.
          </p>
        </div>
      </div>
      <div class="mt-12 pt-8 border-t border-stone-800 text-xs text-stone-500 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>&copy; 2026 Butter Property (butterproperty.com). All rights reserved.</div>
        <div class="flex items-center gap-4">
          <a id="footerWhatsappLink" href="https://wa.me/<?= BP_WHATSAPP_PHONE ?>?text=<?= $waMessage ?>" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 flex items-center gap-1.5 font-semibold">
            <i class="fa-brands fa-whatsapp text-sm"></i>
            <span>WhatsApp Desk</span>
          </a>
          <span class="text-stone-700 hidden sm:inline">•</span>
          <a href="index.html" class="hover:text-stone-300">Home</a>
          <a href="index.html#properties" class="hover:text-stone-300">Properties</a>
          <a href="index.html#why-butter" class="hover:text-stone-300">Why Butter</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ==================== 8. CONFIRMATION TOAST ==================== -->
  <div 
    id="detailToast" 
    class="fixed bottom-6 right-6 z-50 bg-stone-900 text-white px-5 py-4 rounded-md shadow-2xl border border-stone-700 max-w-sm flex items-start gap-3 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none"
  >
    <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 text-sm mt-0.5">
      <i class="fa-solid fa-check"></i>
    </div>
    <div>
      <div class="text-xs font-semibold text-white uppercase tracking-wider">Brief Logged</div>
      <p id="detailToastMsg" class="text-xs text-stone-300 mt-0.5 leading-relaxed">
        Our Senior Partner will dispatch the confidential property dossier within 4 business hours.
      </p>
    </div>
  </div>

  <!-- ==================== 9. FULLSCREEN LIGHTBOX MODAL ==================== -->
  <div 
    id="fullscreenLightboxModal" 
    class="fixed inset-0 z-50 bg-stone-950/95 backdrop-blur-xl hidden flex-col items-center justify-between p-4 sm:p-8 select-none"
    role="dialog"
    aria-modal="true"
  >
    <div class="w-full max-w-6xl flex items-center justify-between text-white z-20 pb-4 border-b border-stone-800">
      <div class="flex items-center gap-3">
        <span class="w-2.5 h-2.5 rounded-full bg-[#FDDB00]"></span>
        <span class="text-xs uppercase tracking-wider font-semibold text-stone-300"><?= htmlspecialchars($prop['title']) ?></span>
      </div>
      <button 
        onclick="closeFullscreenLightbox()" 
        class="w-10 h-10 rounded-full bg-stone-800 hover:bg-stone-700 text-white flex items-center justify-center transition-colors"
      >
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>
    </div>

    <div class="w-full max-w-6xl h-full flex items-center justify-center relative py-4">
      <button 
        onclick="prevGalleryImage()" 
        class="absolute left-2 sm:left-4 z-20 w-11 h-11 rounded-full bg-stone-900/80 hover:bg-stone-800 text-white flex items-center justify-center transition-colors"
      >
        <i class="fa-solid fa-chevron-left"></i>
      </button>

      <img 
        id="lightboxImage" 
        src="<?= htmlspecialchars($prop['mainImage']) ?>" 
        alt="<?= htmlspecialchars($prop['title']) ?>" 
        class="max-w-full max-h-[80vh] object-contain rounded-md shadow-2xl" 
      />

      <button 
        onclick="nextGalleryImage()" 
        class="absolute right-2 sm:right-4 z-20 w-11 h-11 rounded-full bg-stone-900/80 hover:bg-stone-800 text-white flex items-center justify-center transition-colors"
      >
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>

    <div class="w-full max-w-6xl text-center text-xs text-stone-400 pt-3 border-t border-stone-800">
      <span id="lightboxCaption"><?= htmlspecialchars($prop['gallery'][0]['title'] ?? $prop['title']) ?></span>
    </div>
  </div>

  <!-- PHP JSON Injection for Client-Side Interactivity -->
  <script>
    const currentProp = <?= json_encode($prop, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
    let currentCurrency = 'AED';
    let currentImageIndex = 0;
    const galleryItems = currentProp.gallery || [];

    function setDetailCurrency(curr) {
      currentCurrency = curr;
      const btnAed = document.getElementById('detailCurrencyBtnAED');
      const btnInr = document.getElementById('detailCurrencyBtnINR');
      const mobileBadge = document.getElementById('mobileCurrBadge');
      const displayPrice = document.getElementById('displayHeaderPrice');

      if (curr === 'INR') {
        if (btnInr) {
          btnInr.className = 'px-2.5 py-1 rounded-full bg-[#7323DC] text-white font-bold transition-all shadow-xs';
        }
        if (btnAed) {
          btnAed.className = 'px-2.5 py-1 rounded-full transition-all text-stone-500 hover:text-stone-900';
        }
        if (mobileBadge) mobileBadge.textContent = '₹ INR';
        if (displayPrice) displayPrice.textContent = currentProp.formattedPriceINR;
      } else {
        if (btnAed) {
          btnAed.className = 'px-2.5 py-1 rounded-full bg-[#7323DC] text-white font-bold transition-all shadow-xs';
        }
        if (btnInr) {
          btnInr.className = 'px-2.5 py-1 rounded-full transition-all text-stone-500 hover:text-stone-900';
        }
        if (mobileBadge) mobileBadge.textContent = 'AED';
        if (displayPrice) displayPrice.textContent = currentProp.formattedPriceAED;
      }
    }

    function toggleDetailCurrency() {
      setDetailCurrency(currentCurrency === 'AED' ? 'INR' : 'AED');
    }

    function selectGalleryImage(index) {
      if (!galleryItems[index]) return;
      currentImageIndex = index;
      const mainImg = document.getElementById('mainActiveImage');
      const imgTitle = document.getElementById('activeImgTitle');
      const imgCaption = document.getElementById('activeImgCaption');
      const currIdxSpan = document.getElementById('currentImgIndex');

      if (mainImg) mainImg.src = galleryItems[index].url;
      if (imgTitle) imgTitle.textContent = galleryItems[index].title || '';
      if (imgCaption) imgCaption.textContent = galleryItems[index].caption || '';
      if (currIdxSpan) currIdxSpan.textContent = (index + 1).toString();

      // Update thumbnails ring
      const thumbs = document.querySelectorAll('.gallery-thumb');
      thumbs.forEach((t, idx) => {
        if (idx === index) {
          t.classList.add('ring-2', 'ring-[#FDDB00]', 'opacity-100');
          t.classList.remove('opacity-60');
        } else {
          t.classList.remove('ring-2', 'ring-[#FDDB00]', 'opacity-100');
          t.classList.add('opacity-60');
        }
      });
    }

    function nextGalleryImage() {
      const next = (currentImageIndex + 1) % galleryItems.length;
      selectGalleryImage(next);
      updateLightbox();
    }

    function prevGalleryImage() {
      const prev = (currentImageIndex - 1 + galleryItems.length) % galleryItems.length;
      selectGalleryImage(prev);
      updateLightbox();
    }

    function openFullscreenLightbox() {
      const modal = document.getElementById('fullscreenLightboxModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        updateLightbox();
      }
    }

    function closeFullscreenLightbox() {
      const modal = document.getElementById('fullscreenLightboxModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function updateLightbox() {
      const img = document.getElementById('lightboxImage');
      const cap = document.getElementById('lightboxCaption');
      if (img && galleryItems[currentImageIndex]) {
        img.src = galleryItems[currentImageIndex].url;
      }
      if (cap && galleryItems[currentImageIndex]) {
        cap.textContent = `${galleryItems[currentImageIndex].title} — ${galleryItems[currentImageIndex].caption || ''}`;
      }
    }

    // Zoom Lens functionality
    function initGalleryZoom() {
      const frame = document.getElementById('galleryMainViewport');
      const img = document.getElementById('mainActiveImage');
      const hint = document.getElementById('zoomHintBadge');
      if (!frame || !img) return;

      frame.addEventListener('mousemove', (e) => {
        const rect = frame.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        img.style.transformOrigin = `${x}% ${y}%`;
      });

      frame.addEventListener('mouseenter', () => {
        img.classList.remove('zoom-idle');
        img.classList.add('zoom-active');
        if (hint) hint.style.opacity = '0';
      });

      frame.addEventListener('mouseleave', () => {
        img.classList.remove('zoom-active');
        img.classList.add('zoom-idle');
        if (hint) hint.style.opacity = '1';
      });
    }

    async function handleDetailInquiry(e) {
      // If JS is active, submit via AJAX API for immediate feedback without reload
      e.preventDefault();
      const form = e.target;
      const name = document.getElementById('detailClientName')?.value.trim() || '';
      const email = document.getElementById('detailClientEmail')?.value.trim() || '';
      const phone = document.getElementById('detailClientPhone')?.value.trim() || '';
      const timeline = document.getElementById('detailClientTimeline')?.value || 'Immediate Allocation';
      const notes = document.getElementById('detailClientNotes')?.value.trim() || '';

      try {
        const res = await fetch('api/inquiry.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            source: 'Property Dossier Form (PHP-AJAX)',
            name: name,
            email: email,
            phone: phone,
            propertyId: currentProp.id,
            property: currentProp.title,
            region: currentProp.regionLabel,
            timeline: timeline,
            notes: notes
          })
        });
        const result = await res.json();
        
        showToast(result.message || `Confidential brief logged for ${name || 'Principal'}.`);
        form.reset();
      } catch (err) {
        // Fallback to standard form submit if fetch fails
        form.submit();
      }
    }

    function showToast(msg) {
      const toast = document.getElementById('detailToast');
      const msgEl = document.getElementById('detailToastMsg');
      if (msgEl) msgEl.textContent = msg;
      if (toast) {
        toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');
        setTimeout(() => {
          toast.classList.remove('translate-y-0', 'opacity-100');
          toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
        }, 5000);
      }
    }

    function openPropertyInquiryModal() {
      const formEl = document.getElementById('contact-advisor');
      if (formEl) {
        formEl.scrollIntoView({ behavior: 'smooth' });
        const nameInput = document.getElementById('detailClientName');
        if (nameInput) setTimeout(() => nameInput.focus(), 300);
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      initGalleryZoom();
    });
  </script>
</body>
</html>
