/**
 * Butter Property - Core Properties Master Data
 * butterproperty.com
 */

const DEFAULT_PROPERTIES = [
  // ==========================================
  // DUBAI PORTFOLIO (UAE)
  // ==========================================
  {
    id: 'dxb-01',
    slug: 'the-sky-palace-one-canal',
    title: 'The Sky Palace at One Canal',
    tagline: 'Dual-Aspect Panoramic Sky Duplex Overlooking Dubai Canal & Burj Khalifa',
    neighborhood: 'Dubai Water Canal, Downtown District',
    city: 'Dubai',
    country: 'United Arab Emirates',
    region: 'dubai',
    regionLabel: 'Dubai, UAE',
    flag: '🇦🇪',
    type: 'Penthouses',
    tier: 'tier-3', // $6M+
    config: '5 & 6 Bed Sky Duplex',
    bedrooms: 6,
    bathrooms: 8,
    sizeSqFt: 11400,
    priceAED: 24500000,
    priceINR: 555000000, // ₹55.5 Cr
    badge: 'Trophy Asset',
    developer: 'Firas Nehma & AHS Properties',
    completion: 'Q4 2025 (Under Construction / 85% Complete)',
    ownership: 'Freehold Title Deed (100% Foreign Ownership)',
    visaEligible: 'UAE 10-Year Golden Visa Included',
    rentalYield: '7.8% Net Projected Yield',
    serviceCharges: 'AED 22 / sq.ft per annum',
    
    // High-resolution architectural photography
    mainImage: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
    gallery: [
      {
        url: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
        title: 'Exterior Architecture & Water Canal Promontory',
        caption: 'Cantilevered private infinity swimming pools on floating terraces.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80',
        title: 'Double-Height Grand Living Salon',
        caption: '11-meter ceiling heights with book-matched Italian Calacatta marble.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
        title: 'Private Sky Terrace & Heated Pool',
        caption: 'Heated zero-edge pool overlooking the entire Burj Khalifa skyline.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        title: 'Master Bedroom Sanctuary',
        caption: 'Custom poliform walk-in dressing suites and private massage lounge.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
        title: 'Bespoke Show & Prep Kitchens',
        caption: 'Sub-Zero refrigeration and Gaggenau 400 series touch-screen appliances.'
      }
    ],

    description: `Positioned directly along the Dubai Water Canal with uninterrupted sightlines extending to the Burj Khalifa and Downtown skyline, The Sky Palace at One Canal redefines trophy duplex living. Designed by globally acclaimed architectural ateliers, each curve and terrace has been engineered to maximize private outdoor living in the sky.

Featuring an ultra-exclusive car lift option delivering vehicles directly to a private glass sky gallery, a private 15-meter cantilevered infinity pool, dual chef show kitchens, and dedicated staff quarters for four personnel, this residence serves as a multi-generational legacy asset.`,

    highlights: [
      'Private 15-meter cantilevered infinity pool on floating terrace',
      'Full unobstructed Burj Khalifa and Dubai Water Canal skyline views',
      'Dedicated private elevator opening into private foyer',
      'Dual chef kitchens: Gaggenau 400 series and Sub-Zero refrigeration',
      'Rolls-Royce and supercar direct vehicle elevator option',
      'Eligible for immediate UAE 10-Year Golden Visa residency'
    ],

    amenities: [
      { icon: 'fa-water-ladder', name: 'Private Sky Pool' },
      { icon: 'fa-shield-halved', name: '24/7 Diplomatic Security' },
      { icon: 'fa-bell-concierge', name: 'Bespoke Rolls-Royce Chauffeur' },
      { icon: 'fa-spa', name: 'Private Steam & Sauna' },
      { icon: 'fa-utensils', name: 'Michelin-Trained Resident Chef' },
      { icon: 'fa-square-parking', name: '4 Allocated Basement Bays' }
    ],

    financials: {
      annualRentalValueAED: 'AED 1,911,000 / year',
      projectedCapRate: '7.8% Net',
      capitalAppreciationForecast: '9.5% p.a. over 36 months',
      taxImplications: '0% Capital Gains, 0% Rental Income Tax in UAE'
    }
  },

  {
    id: 'dxb-02',
    slug: 'signature-frond-royal-villa-palm-jumeirah',
    title: 'Signature Frond Royal Villa',
    tagline: 'Private White-Sand Shoreline Estate on Palm Jumeirah Frond N',
    neighborhood: 'Palm Jumeirah Frond N',
    city: 'Dubai',
    country: 'United Arab Emirates',
    region: 'dubai',
    regionLabel: 'Palm Jumeirah, Dubai',
    flag: '🇦🇪',
    type: 'Private Villas',
    tier: 'tier-3', // $6M+
    config: '6 Bed Beachfront Estate',
    bedrooms: 6,
    bathrooms: 9,
    sizeSqFt: 14200,
    priceAED: 48000000,
    priceINR: 1085000000, // ₹108.5 Cr
    badge: 'Private Beach Access',
    developer: 'Bespoke Private Commission',
    completion: 'Ready for Immediate Key Handover',
    ownership: 'Freehold Title Deed (100% Foreign Ownership)',
    visaEligible: 'UAE 10-Year Golden Visa Included',
    rentalYield: '8.2% Capitalized ROI',
    serviceCharges: 'AED 16 / sq.ft per annum',

    mainImage: 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=80',
    gallery: [
      {
        url: 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=80',
        title: 'Beachfront Facade & Tropical Landscaping',
        caption: '180 feet of private beachfront with custom travertine architectural fins.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80',
        title: 'Infinity Lap Pool & Private Shoreline',
        caption: 'Heated Olympic-length pool cascading down to calm private turquoise waters.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
        title: 'Grand Rotunda & Modern Living Pavilion',
        caption: 'Seamless indoor-outdoor motorized glass pocket doors spanning 24 meters.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
        title: 'Bespoke Dining Suite & Wine Cellar',
        caption: 'Climate-controlled 600-bottle glass wine gallery adjacent to dining salon.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        title: 'Master En-Suite Spa Sanctuary',
        caption: 'Freestanding monolithic marble bathtub overlooking the tranquil sea.'
      }
    ],

    description: `A rare architectural tour de force situated along the exclusive Frond N of Palm Jumeirah. This ultra-private royal beachfront residence commands 180 feet of pristine direct shoreline with calm, swimmable private waters and horizon views of the Dubai Marina skyline.

Boasting over 14,200 sq.ft of hyper-curated interior living, the villa incorporates automated glass walls that slide silently into wall pockets, erasing the boundary between the internal entertaining pavilions and the private palm-fringed sun decks.`,

    highlights: [
      '180-ft private white-sand beach frontage with direct water access',
      'Private yacht mooring capability directly off the frond buffer',
      'Climate-controlled subterranean car gallery for 6 luxury vehicles',
      'State-of-the-art IMAX private screening theatre with Dolby Atmos',
      'Full smart-home integration with Savant control systems',
      'Turnkey furnished with custom Minotti and B&B Italia collections'
    ],

    amenities: [
      { icon: 'fa-umbrella-beach', name: 'Private Beach Access' },
      { icon: 'fa-ship', name: 'Yacht Mooring Permitted' },
      { icon: 'fa-film', name: '12-Seat IMAX Cinema' },
      { icon: 'fa-wine-bottle', name: 'Sommelier Wine Vault' },
      { icon: 'fa-dumbbell', name: 'Technogym Health Suite' },
      { icon: 'fa-lock', name: 'Gated Private Frond' }
    ],

    financials: {
      annualRentalValueAED: 'AED 3,936,000 / year (Luxury Holiday Rental Yield)',
      projectedCapRate: '8.2% Capitalized',
      capitalAppreciationForecast: '12% p.a. due to Palm Jumeirah land scarcity',
      taxImplications: '0% Property Tax, No Recurring Holding Taxes'
    }
  },

  {
    id: 'dxb-03',
    slug: 'marina-horizon-residence-bluewaters',
    title: 'Marina Horizon Panoramic Residence',
    tagline: 'Dual-Aspect High-Yield Waterfront Haven in Dubai Marina',
    neighborhood: 'Dubai Marina & Bluewaters Enclave',
    city: 'Dubai',
    country: 'United Arab Emirates',
    region: 'dubai',
    regionLabel: 'Dubai Marina, UAE',
    flag: '🇦🇪',
    type: 'Luxury Apartments',
    tier: 'tier-1', // Under $3M
    config: '3 Bed Waterfront Corner Residence',
    bedrooms: 3,
    bathrooms: 4,
    sizeSqFt: 3450,
    priceAED: 8900000,
    priceINR: 201000000, // ₹20.1 Cr
    badge: 'High Rental Yield',
    developer: 'Select Group & Kerzner Partners',
    completion: 'Ready for Immediate Occupation',
    ownership: 'Freehold Title Deed (100% Foreign Ownership)',
    visaEligible: 'UAE 10-Year Golden Visa Included',
    rentalYield: '9.1% High-Yield Cash Flow',
    serviceCharges: 'AED 18 / sq.ft per annum',

    mainImage: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1600&q=80',
    gallery: [
      {
        url: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1600&q=80',
        title: 'Terrace Vantage Point over Dubai Marina',
        caption: 'Overlooking luxury superyacht slips and the shimmering marina promenade.'
      },
      {
        url: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
        title: 'Modern Minimalist Waterfront Living',
        caption: 'Warm oak timber flooring paired with brushed brass hardware accents.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
        title: 'Floor-to-Ceiling Corner Fenestration',
        caption: '270-degree panoramic views of Ain Dubai and the Arabian Gulf sunset.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        title: 'Master En-Suite Dressing & Bath',
        caption: 'Honed Pietra Serena sandstone with concealed rain showers.'
      }
    ],

    description: `Engineered specifically for high-net-worth investors seeking rock-solid rental yield alongside strong capital preservation, Marina Horizon is located on a coveted promontory bridging Dubai Marina and Bluewaters Island.

The residence enjoys immediate walking access to Michelin-starred dining, private yacht charters, and upscale beach clubs, generating exceptional short-to-medium-term corporate yields exceeding 9% net after service expenses.`,

    highlights: [
      'Dual-aspect corner orientation capturing both Marina and sea sunsets',
      'Turnkey asset managed by ultra-luxury hospitality operator',
      'Consistently ranked top 1% for rental yield across Dubai Marina',
      'Direct yacht berth booking privilege for residents',
      'Zero income or capital gains tax structuring in UAE jurisdiction'
    ],

    amenities: [
      { icon: 'fa-anchor', name: 'Direct Marina Berthing' },
      { icon: 'fa-water-ladder', name: 'Rooftop Horizon Pool' },
      { icon: 'fa-dumbbell', name: 'Technogym Health Club' },
      { icon: 'fa-shield-halved', name: '24/7 Concierge & Security' },
      { icon: 'fa-charging-station', name: 'EV Rapid Charging' },
      { icon: 'fa-utensils', name: 'Fine Dining In-Building' }
    ],

    financials: {
      annualRentalValueAED: 'AED 809,900 / year',
      projectedCapRate: '9.1% Net',
      capitalAppreciationForecast: '7.5% p.a.',
      taxImplications: 'Tax-Free Rental Distribution'
    }
  },

  // ==========================================
  // INDIA PORTFOLIO
  // ==========================================
  {
    id: 'ind-01',
    slug: 'worli-sea-face-signature-duplex-mumbai',
    title: 'Worli Sea Face Signature Duplex',
    tagline: 'Unobstructed Arabian Sea Frontage in South Mumbai’s Trophy Postcode',
    neighborhood: 'Worli Sea Face, South Mumbai',
    city: 'Mumbai',
    country: 'India',
    region: 'india',
    regionLabel: 'Mumbai, India',
    flag: '🇮🇳',
    type: 'Penthouses',
    tier: 'tier-3', // $6M+
    config: '4 & 5 BHK Arab Sea Front Sky Villa',
    bedrooms: 5,
    bathrooms: 6,
    sizeSqFt: 7850,
    priceAED: 33000000,
    priceINR: 750000000, // ₹75 Cr
    badge: 'Unobstructed Sea View',
    developer: 'Signature Boutique Private Developer',
    completion: 'Ready for Interior Fitout / Clear OC Received',
    ownership: 'Freehold Clear Title (RERA Approved & Audited)',
    visaEligible: 'NRI & OCI Compliant Under FEMA Regulations',
    rentalYield: 'Trophy Legacy Capital Appreciation',
    serviceCharges: '₹ 25 / sq.ft per month',

    mainImage: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
    gallery: [
      {
        url: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
        title: 'Arabian Sea Front Architectural Living',
        caption: 'Expansive wrap-around glass balconies greeting the Worli waves.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80',
        title: 'Grand Sea-Facing Living Pavilion',
        caption: '14-foot clear slab-to-slab ceiling heights and imported Statuario marble.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
        title: 'Bespoke Private Library & Cigar Lounge',
        caption: 'Crafted with acoustic walnut panelling and discrete security vaults.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        title: 'Master Bedroom with Coastal Sunset Views',
        caption: 'Double-glazed acoustic acoustic glass isolating the ambient coastal soundscape.'
      }
    ],

    description: `Worli Sea Face remains the most revered residential promenade in the subcontinent. This expansive sky duplex occupies the premium highest residential levels, framing an unbroken 180-degree vista of the Arabian Sea and the Bandra-Worli Sea Link.

Crafted with 14-foot slab-to-slab ceiling heights, dual high-speed private biometric elevators, separate service cores, and 5 dedicated covered parking bays, this residence accommodates the highest echelon of Indian industrial and financial leadership.`,

    highlights: [
      'Unbroken, permanent Arabian Sea panorama with zero obstruction risk',
      'Full Occupancy Certificate (OC) received; zero GST on purchase',
      'Dual high-speed private biometric passenger elevators opening directly in suite',
      '5 designated covered mechanical parking bays in prime podium',
      'Separate service entrance and quarters for 3 live-in domestic staff',
      'Vetted by senior counsel for 100% marketable and unencumbered title'
    ],

    amenities: [
      { icon: 'fa-water', name: 'Unobstructed Sea View' },
      { icon: 'fa-elevator', name: 'Private Biometric Lifts' },
      { icon: 'fa-shield-halved', name: 'Z+ Level Security Protocol' },
      { icon: 'fa-car', name: '5 Covered Parking Bays' },
      { icon: 'fa-spa', name: 'Heated Rooftop Infinity Pool' },
      { icon: 'fa-users-gear', name: 'Dedicated Butler Core' }
    ],

    financials: {
      annualRentalValueAED: 'AED 1,320,000 / year (₹ 3.0 Cr / yr Corporate Lease)',
      projectedCapRate: '4.2% Prime South Mumbai Yield',
      capitalAppreciationForecast: '14% p.a. Driven by Coastal Road Connectivity',
      taxImplications: 'Capital Gains Rollover Eligible under Sec 54'
    }
  },

  {
    id: 'ind-02',
    slug: 'ub-city-imperial-sky-residence-bengaluru',
    title: 'UB City Imperial Sky Residences',
    tagline: 'Ultra-Luxury Condominium Overlooking Cubbon Park Canopies',
    neighborhood: 'Vittal Mallya Road, Bengaluru',
    city: 'Bengaluru',
    country: 'India',
    region: 'india',
    regionLabel: 'Bengaluru, India',
    flag: '🇮🇳',
    type: 'Luxury Apartments',
    tier: 'tier-2', // $3M-$6M
    config: '4 BHK Ultra-Luxury Condominium',
    bedrooms: 4,
    bathrooms: 5,
    sizeSqFt: 5600,
    priceAED: 12500000,
    priceINR: 285000000, // ₹28.5 Cr
    badge: 'Prime CBD Address',
    developer: 'Prestige Group & Private JV',
    completion: 'Ready for Occupation',
    ownership: 'Freehold RERA Registered Title',
    visaEligible: 'NRI / OCI FEMA Compliant',
    rentalYield: '6.4% Prime CBD Rental Yield',
    serviceCharges: '₹ 18 / sq.ft per month',

    mainImage: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
    gallery: [
      {
        url: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
        title: 'Architectural Panorama over Vittal Mallya Road',
        caption: 'Overlooking lush 300-acre Cubbon Park tree canopies.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
        title: 'Expansive Formal Entertaining Salon',
        caption: 'Italian marble cladding with bespoke fluted bronze divider screens.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80',
        title: 'Private Sky Balcony & Green Buffer',
        caption: 'Triple-aspect green view unmatched in central Bengaluru.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        title: 'Master Retreat & En-Suite Dresser',
        caption: 'Integrated smart climate and humidity control systems.'
      }
    ],

    description: `Occupying Bengaluru's most prestigious micro-market on Vittal Mallya Road, adjacent to the UB City luxury shopping arcade and Cubbon Park. This high-floor residence offers quiet grandeur in the center of India's Silicon capital.

Favored by founders, tech titans, and venture capitalists, the tower features a private rooftop helipad, temperature-controlled Olympic pool, private boardrooms, and white-glove concierge services.`,

    highlights: [
      'Direct green views across Cubbon Park canopy in Central Bengaluru',
      'Walking proximity to luxury retail, fine dining, and private bank desks',
      'Exclusive rooftop helipad access for resident principals',
      'LEED Platinum certified sustainable architectural build',
      'Highest rental demand micro-market for multinational C-suite executives'
    ],

    amenities: [
      { icon: 'fa-helicopter', name: 'Private Rooftop Helipad' },
      { icon: 'fa-tree', name: 'Cubbon Park Facing' },
      { icon: 'fa-water-ladder', name: 'Temperature Controlled Pool' },
      { icon: 'fa-handshake', name: 'Private Executive Boardrooms' },
      { icon: 'fa-shield-halved', name: 'Multi-Tier Security' },
      { icon: 'fa-car', name: '4 Reserved Parking Bays' }
    ],

    financials: {
      annualRentalValueAED: 'AED 800,000 / year (₹ 1.82 Cr / yr)',
      projectedCapRate: '6.4% Net Yield',
      capitalAppreciationForecast: '10.5% p.a.',
      taxImplications: 'Indexation & Tax Shield Available'
    }
  },

  {
    id: 'ind-03',
    slug: 'the-camellias-grand-sovereign-suite-gurugram',
    title: 'The Camellias Grand Sovereign Suite',
    tagline: 'Championship Golf Front Sky Manor on DLF Golf Course Road',
    neighborhood: 'Golf Course Road, Gurugram (NCR)',
    city: 'Gurugram',
    country: 'India',
    region: 'india',
    regionLabel: 'Gurugram, India',
    flag: '🇮🇳',
    type: 'Luxury Apartments',
    tier: 'tier-2', // $3M-$6M
    config: '4 & 5 BHK Golf-Facing Sky Manor',
    bedrooms: 5,
    bathrooms: 6,
    sizeSqFt: 7400,
    priceAED: 18800000,
    priceINR: 425000000, // ₹42.5 Cr
    badge: 'Championship Golf Front',
    developer: 'DLF Luxury Division',
    completion: 'Ready for Immediate Occupation',
    ownership: 'Freehold Title Deed (RERA Approved)',
    visaEligible: 'FEMA / NRI Compliant',
    rentalYield: 'Highest Tier Appreciation in NCR',
    serviceCharges: '₹ 22 / sq.ft per month',

    mainImage: 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1600&q=80',
    gallery: [
      {
        url: 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1600&q=80',
        title: 'Golf Front Panoramic Promenade',
        caption: 'Overlooking Gary Player and Arnold Palmer 18-hole championship greens.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
        title: 'Triple-Aspect Glass Living Pavilion',
        caption: '12-foot column-free glass spans overlooking the fairway water bodies.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
        title: 'Master Bedroom Suite & Balcony',
        caption: 'Soundproofed double-laminated glazing for absolute serenity.'
      },
      {
        url: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        title: 'Bespoke Kitchen & Sommelier Space',
        caption: 'Custom stone countertops with built-in European wine cooler.'
      }
    ],

    description: `The Camellias is universally recognized as India's premier super-luxury residential community, matching and exceeding luxury residential benchmarks in Singapore and London.

Directly overlooking the Gary Player 18-hole championship golf course, the Grand Sovereign Suite boasts a 130,000 sq.ft private clubhouse featuring a full-service spa, indoor heated pools, boxing ring, simulation golf, and private private dining restaurants curated by award-winning chefs.`,

    highlights: [
      'Frontline panoramic view over 18-hole Gary Player championship golf course',
      'Exclusive membership to 130,000 sq.ft ultra-luxury clubhouse and health center',
      '7-tier institutional security with centralized command room',
      'Dedicated golf cart tracks and private clubhouse access',
      'Proven capital appreciation with unmatched prestige across North India'
    ],

    amenities: [
      { icon: 'fa-golf-ball-tee', name: '18-Hole Championship Golf' },
      { icon: 'fa-building-columns', name: '130,000 sq.ft Clubhouse' },
      { icon: 'fa-spa', name: 'Six Senses Style Wellness Spa' },
      { icon: 'fa-water-ladder', name: 'Indoor Heated Olympic Pool' },
      { icon: 'fa-shield-halved', name: '7-Tier Security Architecture' },
      { icon: 'fa-car', name: '4 Underground Secured Bays' }
    ],

    financials: {
      annualRentalValueAED: 'AED 1,150,000 / year (₹ 2.6 Cr / yr)',
      projectedCapRate: '6.1% Net Yield',
      capitalAppreciationForecast: '15% p.a. High Scarcity Premium',
      taxImplications: 'Capital Gains Tax Shield under Indian IT Act'
    }
  }
];

// ==========================================
// DYNAMIC LOCALSTORAGE SYNCHRONIZATION
// ==========================================
function getLiveProperties() {
  try {
    if (typeof localStorage !== 'undefined') {
      const custom = localStorage.getItem('butter_custom_properties');
      if (custom) {
        const parsed = JSON.parse(custom);
        if (Array.isArray(parsed) && parsed.length > 0) {
          return parsed;
        }
      }
    }
  } catch (e) {
    console.warn('Using default master property data:', e);
  }
  return DEFAULT_PROPERTIES;
}

// Active Global Properties Reference
let PROPERTIES = getLiveProperties();

function savePropertiesToStorage(props) {
  PROPERTIES = props;
  if (typeof localStorage !== 'undefined') {
    localStorage.setItem('butter_custom_properties', JSON.stringify(props));
  }
}

function resetPropertiesStorage() {
  if (typeof localStorage !== 'undefined') {
    localStorage.removeItem('butter_custom_properties');
  }
  PROPERTIES = JSON.parse(JSON.stringify(DEFAULT_PROPERTIES));
  return PROPERTIES;
}

// ==========================================
// WORKFLOW & LEAD MANAGEMENT HELPERS
// ==========================================
function getStoredLeads() {
  try {
    if (typeof localStorage !== 'undefined') {
      const data = localStorage.getItem('butter_leads');
      if (data) {
        const parsed = JSON.parse(data);
        if (Array.isArray(parsed)) return parsed;
      }
    }
  } catch (e) {
    console.warn('Error reading stored leads:', e);
  }
  return [];
}

function recordLeadInquiry(leadData) {
  try {
    const leads = getStoredLeads();
    const newLead = {
      id: 'BP-' + Date.now().toString(36).toUpperCase(),
      createdAt: new Date().toISOString(),
      status: 'New Inquiry', // 'New Inquiry', 'NDA Sent', 'Dossier Dispatched', 'Inspection Booked', 'Under Negotiation', 'Closed Won', 'Archived'
      source: leadData.source || 'Website Portal',
      name: leadData.name || 'Anonymous Investor',
      email: leadData.email || 'N/A',
      phone: leadData.phone || 'N/A',
      property: leadData.property || 'General Advisory',
      propertyId: leadData.propertyId || '',
      region: leadData.region || 'Both (India & Dubai)',
      timeline: leadData.timeline || 'Immediate Allocation (30 Days)',
      notes: leadData.notes || '',
      advisorNotes: leadData.advisorNotes || '',
      assignedAdvisor: leadData.assignedAdvisor || 'Managing Partner Desk',
      estimatedBudget: leadData.estimatedBudget || '',
      ...leadData
    };
    leads.unshift(newLead);
    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('butter_leads', JSON.stringify(leads));
    }
    return newLead;
  } catch (e) {
    console.error('Error saving lead inquiry:', e);
  }
}

// Node.js module export support
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    DEFAULT_PROPERTIES,
    PROPERTIES,
    getPropertyById,
    getPropertyBySlug,
    getRelatedProperties,
    getLiveProperties,
    savePropertiesToStorage,
    resetPropertiesStorage,
    getStoredLeads,
    recordLeadInquiry
  };
}

// Helper Functions
function getPropertyById(id) {
  return PROPERTIES.find(p => p.id === id) || null;
}

function getPropertyBySlug(slug) {
  return PROPERTIES.find(p => p.slug === slug) || null;
}

function getRelatedProperties(currentId, limit = 2) {
  return PROPERTIES.filter(p => p.id !== currentId).slice(0, limit);
}
