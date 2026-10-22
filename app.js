/**
 * Butter Property - High-Net-Worth Boutique Real Estate Web Application
 * butterproperty.com
 */

// 1. Application State
const state = {
  currency: localStorage.getItem('butter_currency') || 'AED', // 'AED' or 'INR'
  regionFilter: localStorage.getItem('butter_filter_region') || 'all', // 'all', 'dubai', 'india'
  typeFilter: 'all',
  budgetFilter: 'all',
  activeRegionTab: 'all'
};

// 2. Formatting Utilities
function formatPrice(amount, currency) {
  if (currency === 'AED') {
    return `AED ${amount.toLocaleString('en-US')}`;
  } else {
    // Format Indian Rupees in Crores
    const crores = (amount / 10000000).toFixed(1);
    const formattedCrores = crores.endsWith('.0') ? crores.slice(0, -2) : crores;
    return `₹ ${formattedCrores} Cr`;
  }
}

// 3. Render Curated Property Showcase
function renderProperties() {
  const grid = document.getElementById('propertyGrid');
  const emptyState = document.getElementById('emptyState');
  const countDisplay = document.getElementById('propertyCountDisplay');

  if (!grid) return;

  // Ensure PROPERTIES is defined from properties-data.js
  const sourceList = (typeof PROPERTIES !== 'undefined') ? PROPERTIES : [];

  // Filter properties according to state
  const filtered = sourceList.filter(item => {
    // Region check (from select or quick tabs)
    if (state.regionFilter !== 'all' && item.region !== state.regionFilter) {
      return false;
    }
    // Type check
    if (state.typeFilter !== 'all' && item.type !== state.typeFilter) {
      return false;
    }
    // Budget check
    if (state.budgetFilter !== 'all' && item.tier !== state.budgetFilter) {
      return false;
    }
    return true;
  });

  // Update counter
  if (countDisplay) {
    countDisplay.textContent = `Showing ${filtered.length} Handpicked ${filtered.length === 1 ? 'Property' : 'Properties'}`;
  }

  // Handle Empty State
  if (filtered.length === 0) {
    grid.innerHTML = '';
    if (emptyState) emptyState.classList.remove('hidden');
    return;
  } else {
    if (emptyState) emptyState.classList.add('hidden');
  }

  // Generate HTML with Single Property Page Links
  grid.innerHTML = filtered.map(item => {
    const formattedPrice = formatPrice(state.currency === 'AED' ? item.priceAED : item.priceINR, state.currency);
    const secondaryPrice = state.currency === 'AED' 
      ? formatPrice(item.priceINR, 'INR') 
      : formatPrice(item.priceAED, 'AED');

    return `
      <article class="property-card bg-white border border-stone-200/90 rounded-md overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
        
        <!-- Image & Badges Container -->
        <div>
          <a href="property.html?id=${item.id}" class="block relative overflow-hidden aspect-[16/10] bg-stone-100 cursor-pointer">
            <img 
              src="${item.mainImage || item.image}" 
              alt="${item.title} - ${item.neighborhood}"
              class="property-img w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-108"
              loading="lazy"
            />
            
            <!-- Location Badge -->
            <div class="absolute top-3 left-3 bg-stone-900/90 backdrop-blur-md text-white text-[11px] font-semibold tracking-wider uppercase px-2.5 py-1 rounded shadow-xs flex items-center gap-1.5 border border-stone-700">
              <span>${item.flag}</span>
              <span>${item.regionLabel}</span>
            </div>

            <!-- Distinct Badge -->
            <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md text-stone-900 text-[10px] font-bold tracking-widest uppercase px-2.5 py-1 rounded shadow-xs border border-stone-200">
              ${item.badge}
            </div>

            <!-- Yield / Status Tag -->
            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between pointer-events-none">
              <span class="bg-stone-950/85 backdrop-blur-sm text-stone-200 text-[10px] tracking-wider px-2 py-0.5 rounded font-medium">
                ${item.config}
              </span>
              <span class="bg-emerald-950/85 text-emerald-300 border border-emerald-800/60 text-[10px] tracking-wider px-2 py-0.5 rounded font-medium">
                ${item.sizeSqFt.toLocaleString()} sq.ft
              </span>
            </div>
          </a>

            <!-- Card Content Body -->
          <div class="p-5 sm:p-6">
            
            <div class="flex items-center justify-between text-xs text-stone-500 font-medium mb-1">
              <span class="font-bold uppercase tracking-wider text-[10px] px-2 py-0.5 rounded border ${
                item.region === 'dubai'
                  ? 'text-[#7323DC] bg-[#F4EDFD] border-[#E4D2FA]'
                  : 'text-[#8C6D00] bg-[#FFFDE6] border-[#FAE875]'
              }">
                ${item.type}
              </span>
              <span class="text-stone-500 font-medium text-[11px]">${item.rentalYield || item.yieldProjection}</span>
            </div>

            <h3 class="text-lg font-semibold text-stone-950 tracking-tight group-hover:text-stone-700 transition-colors">
              <a href="property.html?id=${item.id}" class="hover:underline underline-offset-2">
                ${item.title}
              </a>
            </h3>

            <p class="text-xs text-stone-500 mt-1 flex items-center gap-1">
              <i class="fa-solid fa-location-dot text-[#7323DC] text-[10px]"></i>
              <span>${item.neighborhood}</span>
            </p>

            <!-- Key Feature Bullets with Golden Shimmer Stars -->
            <ul class="mt-4 pt-4 border-t border-stone-100 space-y-1.5 text-xs text-stone-600">
              ${item.highlights.slice(0, 3).map(h => `
                <li class="flex items-start gap-2">
                  <span class="text-[#FDDB00] mt-0.5 text-[10px]">✦</span>
                  <span class="leading-tight">${h}</span>
                </li>
              `).join('')}
            </ul>

          </div>
        </div>

        <!-- Card Footer & Pricing with Dual Actions: Purple View + Butter Yellow Brochure -->
        <div class="px-5 pb-5 pt-2 sm:px-6 sm:pb-6">
          <div class="pt-4 border-t border-stone-100 flex items-center justify-between gap-2.5">
            <div>
              <div class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Guide Price</div>
              <div class="text-base sm:text-lg font-bold text-stone-950 tracking-tight leading-none mt-1">
                ${formattedPrice}
              </div>
              <div class="text-[10px] text-stone-400 mt-0.5">
                ≈ ${secondaryPrice}
              </div>
            </div>

            <!-- Dual Action: Purple View + Butter Yellow Brochure -->
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
              <a 
                href="property.html?id=${item.id}" 
                class="px-2.5 py-1.5 sm:px-3 sm:py-2 bg-[#F4EDFD] hover:bg-[#E4D2FA] text-[#7323DC] text-[11px] font-bold uppercase tracking-wider rounded-sm transition-colors border border-[#E4D2FA]"
                title="View full specs, gallery & financials"
              >
                View
              </a>
              <button 
                onclick="openInquiryModal('${item.title} (${item.regionLabel})')"
                class="px-2.5 py-1.5 sm:px-3.5 sm:py-2 bg-[#FDDB00] hover:bg-[#FFE524] text-stone-950 text-[11px] font-bold uppercase tracking-wider rounded-sm transition-colors flex items-center gap-1 shadow-xs border border-[#E5C700]"
              >
                <span>Brochure</span>
              </button>
            </div>
          </div>
        </div>

      </article>
    `;
  }).join('');
}

// 4. Currency Handling
function setCurrency(currency) {
  state.currency = currency;
  localStorage.setItem('butter_currency', currency);
  
  // Update desktop toggle styling
  const btnINR = document.getElementById('currencyBtnINR');
  const btnAED = document.getElementById('currencyBtnAED');
  const filterToggleINR = document.getElementById('filterToggleINR');
  const filterToggleAED = document.getElementById('filterToggleAED');
  const mCurrINR = document.getElementById('mCurrINR');
  const mCurrAED = document.getElementById('mCurrAED');
  const mobileBadge = document.getElementById('mobileCurrencyBadge');

  if (currency === 'AED') {
    if (btnAED) btnAED.className = 'px-2.5 py-1 rounded-full bg-[#7323DC] text-white font-bold transition-all shadow-xs';
    if (btnINR) btnINR.className = 'px-2.5 py-1 rounded-full text-stone-500 hover:text-stone-950 transition-all font-medium';
    
    if (filterToggleAED) filterToggleAED.className = 'font-bold text-[#7323DC] underline underline-offset-2';
    if (filterToggleINR) filterToggleINR.className = 'text-stone-500 hover:text-stone-950 underline-offset-2';

    if (mCurrAED) mCurrAED.className = 'px-2.5 py-0.5 text-xs rounded-full bg-[#7323DC] text-white font-bold';
    if (mCurrINR) mCurrINR.className = 'px-2.5 py-0.5 text-xs rounded-full text-stone-600 font-medium';

    if (mobileBadge) mobileBadge.textContent = 'AED';
    
    const heroAssetPrice = document.getElementById('heroAssetPrice');
    if (heroAssetPrice) heroAssetPrice.textContent = 'AED 24,500,000';
  } else {
    if (btnINR) btnINR.className = 'px-2.5 py-1 rounded-full bg-[#7323DC] text-white font-bold transition-all shadow-xs';
    if (btnAED) btnAED.className = 'px-2.5 py-1 rounded-full text-stone-500 hover:text-stone-950 transition-all font-medium';

    if (filterToggleINR) filterToggleINR.className = 'font-bold text-[#7323DC] underline underline-offset-2';
    if (filterToggleAED) filterToggleAED.className = 'text-stone-500 hover:text-stone-950 underline-offset-2';

    if (mCurrINR) mCurrINR.className = 'px-2.5 py-0.5 text-xs rounded-full bg-[#7323DC] text-white font-bold';
    if (mCurrAED) mCurrAED.className = 'px-2.5 py-0.5 text-xs rounded-full text-stone-600 font-medium';

    if (mobileBadge) mobileBadge.textContent = '₹ INR';

    const heroAssetPrice = document.getElementById('heroAssetPrice');
    if (heroAssetPrice) heroAssetPrice.textContent = '₹ 55.5 Cr';
  }

  renderProperties();
}

function toggleCurrencyMobile() {
  setCurrency(state.currency === 'AED' ? 'INR' : 'AED');
}

// 5. Filter Handling
function applyFilters() {
  const regionSelect = document.getElementById('filterRegion');
  const typeSelect = document.getElementById('filterType');
  const budgetSelect = document.getElementById('filterBudget');

  if (regionSelect) state.regionFilter = regionSelect.value;
  if (typeSelect) state.typeFilter = typeSelect.value;
  if (budgetSelect) state.budgetFilter = budgetSelect.value;

  // Sync quick region tabs
  updateRegionTabUI(state.regionFilter);

  renderProperties();
}

function resetFilters() {
  const regionSelect = document.getElementById('filterRegion');
  const typeSelect = document.getElementById('filterType');
  const budgetSelect = document.getElementById('filterBudget');

  if (regionSelect) regionSelect.value = 'all';
  if (typeSelect) typeSelect.value = 'all';
  if (budgetSelect) budgetSelect.value = 'all';

  state.regionFilter = 'all';
  state.typeFilter = 'all';
  state.budgetFilter = 'all';

  localStorage.removeItem('butter_filter_region');

  updateRegionTabUI('all');
  renderProperties();
}

function setRegionTab(region) {
  state.regionFilter = region;
  const regionSelect = document.getElementById('filterRegion');
  if (regionSelect) regionSelect.value = region;

  updateRegionTabUI(region);
  renderProperties();
}

function filterByQuickRegion(region) {
  setRegionTab(region);
  const target = document.getElementById('properties');
  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
}

function updateRegionTabUI(region) {
  const tabAll = document.getElementById('tabAll');
  const tabDubai = document.getElementById('tabDubai');
  const tabIndia = document.getElementById('tabIndia');

  const activeClasses = 'px-4 py-2 rounded-xs transition-all bg-stone-900 text-white font-semibold';
  const inactiveClasses = 'px-4 py-2 rounded-xs transition-all text-stone-600 hover:text-stone-900';

  if (tabAll) tabAll.className = region === 'all' ? activeClasses : inactiveClasses;
  if (tabDubai) tabDubai.className = region === 'dubai' ? activeClasses : inactiveClasses;
  if (tabIndia) tabIndia.className = region === 'india' ? activeClasses : inactiveClasses;
}

// 6. Modal Handlers
function openInquiryModal(propertyName = 'General Private Consultation') {
  const modal = document.getElementById('inquiryModal');
  const propInput = document.getElementById('modalPropertyName');
  
  if (propInput) {
    propInput.value = propertyName;
  }
  
  if (modal) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    
    // Focus first input
    setTimeout(() => {
      const nameInput = document.getElementById('modalName');
      if (nameInput) nameInput.focus();
    }, 100);
  }
}

function closeInquiryModal() {
  const modal = document.getElementById('inquiryModal');
  if (modal) {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
  }
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeInquiryModal();
  }
});

// 7. Form Submission & Toast Notifications
function handleInquirySubmit(event, type) {
  event.preventDefault();

  let name = '';
  let email = '';
  let phone = '';
  let property = '';
  let timeline = '';
  let notes = '';
  let budget = '';

  if (type === 'modal') {
    name = document.getElementById('modalName')?.value.trim() || '';
    phone = document.getElementById('modalPhone')?.value.trim() || '';
    email = document.getElementById('modalEmail')?.value.trim() || '';
    timeline = document.getElementById('modalTimeline')?.value || 'Next 30 Days';
    property = document.getElementById('modalPropertyName')?.value || 'General Consultation';
    notes = document.getElementById('modalNotes')?.value.trim() || '';
    
    closeInquiryModal();
    document.getElementById('modalInquiryForm')?.reset();
  } else {
    name = document.getElementById('inlineName')?.value.trim() || '';
    email = document.getElementById('inlineEmail')?.value.trim() || '';
    phone = document.getElementById('inlinePhone')?.value.trim() || '';
    property = document.getElementById('inlineRegion')?.value || 'Bespoke Advisory';
    timeline = document.getElementById('inlineTimeline')?.value || 'Immediate Allocation';
    budget = document.getElementById('inlineBudget')?.value || '';
    notes = document.getElementById('inlineNotes')?.value.trim() || '';

    document.getElementById('inlineInquiryForm')?.reset();
  }

  // Persist into workflow CRM if helper is available
  if (typeof recordLeadInquiry === 'function') {
    recordLeadInquiry({
      source: type === 'modal' ? 'Modal Consultation' : 'Homepage Inline Brief',
      name: name || 'Anonymous Principal',
      email: email,
      phone: phone,
      property: property,
      timeline: timeline,
      estimatedBudget: budget,
      notes: notes
    });
  }

  // Sync to PHP backend API if available
  try {
    fetch('api/inquiry.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        source: type === 'modal' ? 'Modal Consultation (Web)' : 'Homepage Inline Brief (Web)',
        name: name,
        email: email,
        phone: phone,
        property: property,
        timeline: timeline,
        estimatedBudget: budget,
        notes: notes
      })
    }).catch(() => {});
  } catch (e) {}

  showToast(`Thank you, ${name || 'Investor'}. Your mandate for "${property}" has been securely logged. Our Senior Principal will contact you shortly.`);
}

function showToast(message) {
  const toast = document.getElementById('toastNotification');
  const msgEl = document.getElementById('toastMessage');

  if (!toast) return;

  if (msgEl) {
    msgEl.textContent = message;
  }

  toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
  toast.classList.add('translate-y-0', 'opacity-100');

  setTimeout(() => {
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
  }, 5000);
}

// 8. Mobile Menu Toggle
function toggleMobileMenu() {
  const menu = document.getElementById('mobileMenu');
  const icon = document.getElementById('mobileMenuIcon');
  
  if (menu) {
    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
      menu.classList.remove('hidden');
      if (icon) icon.className = 'fa-solid fa-xmark text-lg';
    } else {
      menu.classList.add('hidden');
      if (icon) icon.className = 'fa-solid fa-bars text-lg';
    }
  }
}

// 9. Initialization
document.addEventListener('DOMContentLoaded', () => {
  // Set current copyright year
  const yearEl = document.getElementById('currentYear');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // Restore stored region filter if any
  const savedRegion = localStorage.getItem('butter_filter_region');
  if (savedRegion) {
    state.regionFilter = savedRegion;
    const regionSelect = document.getElementById('filterRegion');
    if (regionSelect) regionSelect.value = savedRegion;
    updateRegionTabUI(savedRegion);
    localStorage.removeItem('butter_filter_region');
  }

  // Set initial currency
  setCurrency(state.currency);

  // Initial render
  renderProperties();
});
