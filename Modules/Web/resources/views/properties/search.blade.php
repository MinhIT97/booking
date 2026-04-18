@extends('web::components.layouts.web')

@section('title', 'Properties Search — StayNest')

@push('styles')
<style>
    .filter-chip { transition: all .2s ease; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .filter-chip.active, .filter-chip:hover { background: #ff385c; color: #fff; border-color: #ff385c; }
    .filter-chip.active .filter-icon, .filter-chip:hover .filter-icon { filter: brightness(0) invert(1); }
    
    input[type=range] { accent-color: #ff385c; }
    .sidebar-open { transform: translateX(0) !important; }
    
    /* Pagination Overrides */
    .pagination { display: flex; gap: 8px; align-items: center; }
    .pagination li { list-style: none; }
    .pagination li a, .pagination li span { 
        display: flex; width: 40px; height: 40px; align-items: center; justify-content: center; 
        border: 1px solid #e5e7eb; border-radius: 12px; font-weight: 500; font-size: 14px; 
        background: white; transition: all .2s ease;
    }
    .pagination li.active span { background: #ff385c; color: white; border-color: #ff385c; }
    .pagination li a:hover { border-color: #ff385c; color: #ff385c; }
    .pagination li.disabled span { opacity: 0.4; cursor: not-allowed; }
</style>
@endpush

@section('content')
  <!-- NAV (MATCHING TEMPLATE) -->
  <nav class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
      <a href="{{ route('landing') }}" class="flex items-center gap-2 flex-shrink-0">
        <svg class="w-7 h-7 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span class="text-lg font-bold text-gray-900">Stay<span class="text-brand">Nest</span></span>
      </a>

      <!-- Inline search bar -->
      <form action="{{ route('properties.search') }}" method="GET" class="hidden sm:flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3 py-2 shadow-sm flex-1 max-w-lg">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input name="location" type="text" value="{{ is_array(request('location')) ? '' : request('location') }}" placeholder="Search by city or name…" class="flex-1 text-sm bg-transparent focus:outline-none text-gray-700 placeholder-gray-400" />
        <button type="submit" class="hidden"></button>
      </form>

      <div class="flex items-center gap-3">
        <!-- Mobile filter toggle -->
        <button id="filter-toggle" class="sm:hidden flex items-center gap-1.5 text-sm font-medium text-gray-700 border border-gray-200 px-3 py-2 rounded-xl hover:border-brand hover:text-brand transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
          Filters
        </button>
        @auth
            <a href="{{ route('host.properties.index') }}" class="btn-primary text-white text-sm font-semibold px-4 py-2 rounded-full">Host Rooms</a>
        @else
            <a href="/login" class="hidden sm:block text-sm font-medium text-gray-700 hover:text-brand transition-colors">Sign in</a>
            <a href="{{ route('host.properties.index') }}" class="btn-primary text-white text-sm font-semibold px-4 py-2 rounded-full">Host a room</a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- LAYOUT -->
  <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex gap-8">

      <!-- SIDEBAR FILTER -->
      <aside id="filter-sidebar" class="fixed sm:relative inset-y-0 left-0 sm:inset-auto w-72 sm:w-64 lg:w-72 bg-white sm:bg-transparent z-50 sm:z-auto overflow-y-auto sm:overflow-visible shadow-2xl sm:shadow-none transform -translate-x-full sm:translate-x-0 transition-transform duration-300 flex-shrink-0">
        <form id="filter-form" action="{{ route('properties.search') }}" method="GET" class="p-6 sm:p-0 space-y-8">
          @if(request('location') && !is_array(request('location')))
             <input type="hidden" name="location" value="{{ request('location') }}">
          @endif
          @if(request('dates'))
             <input type="hidden" name="dates" value="{{ request('dates') }}">
          @endif

          <!-- Close on mobile -->
          <div class="flex items-center justify-between sm:hidden">
            <h2 class="font-bold text-gray-900">Filters</h2>
            <button type="button" id="filter-close" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>

          <!-- Property Type -->
          <div>
            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Property Type</h3>
            <div class="flex flex-wrap gap-2">
              <input type="hidden" name="type" id="type-input" value="{{ request('type', 'all') }}">
              <span class="filter-chip border border-gray-200 text-sm px-3 py-1.5 rounded-full {{ request('type', 'all') == 'all' ? 'active' : '' }}" onclick="setType('all')">
                  All
              </span>
              @foreach($categories as $cat)
                <span class="filter-chip border border-gray-200 text-sm px-3 py-1.5 rounded-full {{ request('type') == $cat->slug ? 'active' : '' }}" onclick="setType('{{ $cat->slug }}')">
                  {{ $cat->icon }} {{ $cat->name }}
                </span>
              @endforeach
            </div>
          </div>

          <!-- Price Range -->
          <div>
            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Price per night</h3>
            <div class="space-y-3">
              <div class="flex justify-between text-sm text-gray-600">
                <span>$<span id="price-min-val">{{ request('min_price', 0) }}</span></span>
                <span>$<span id="price-max-val">{{ request('max_price', 1000) }}</span></span>
              </div>
              <div class="space-y-2">
                <input id="price-min" name="min_price" type="range" min="0" max="1000" value="{{ request('min_price', 0) }}" step="10" class="w-full" oninput="syncPriceSlider()" onchange="this.form.submit()" />
                <input id="price-max" name="max_price" type="range" min="0" max="1000" value="{{ request('max_price', 1000) }}" step="10" class="w-full" oninput="syncPriceSlider()" onchange="this.form.submit()" />
              </div>
            </div>
          </div>

          <!-- Location (DYNAMIC CHECKBOXES) -->
          <div>
            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Location</h3>
            <div class="space-y-2">
              @php $selectedLocs = (array) request('location'); @endphp
              @forelse($locations as $loc)
                <label class="flex items-center gap-2.5 text-sm text-gray-700 cursor-pointer group">
                  <input type="checkbox" name="location[]" value="{{ $loc }}" class="loc-check w-4 h-4 accent-brand rounded" onchange="this.form.submit()" {{ in_array($loc, $selectedLocs) ? 'checked' : '' }} />
                  <span class="group-hover:text-brand transition-colors flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-gray-400 group-hover:text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg> 
                      {{ $loc }}
                  </span>
                </label>
              @empty
                <p class="text-gray-400 text-xs italic">No locations found</p>
              @endforelse
            </div>
          </div>

          <!-- Rating -->
          <div>
            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Min Rating</h3>
            <div class="flex gap-2">
              <input type="hidden" name="min_rating" id="rating-input" value="{{ request('min_rating', 0) }}">
              <span class="filter-chip border border-gray-200 text-sm px-3 py-1.5 rounded-full {{ request('min_rating', 0) == 0 ? 'active' : '' }}" onclick="setRating(0)">Any</span>
              <span class="filter-chip border border-gray-200 text-sm px-3 py-1.5 rounded-full {{ request('min_rating') == 4 ? 'active' : '' }}" onclick="setRating(4)">4+</span>
              <span class="filter-chip border border-gray-200 text-sm px-3 py-1.5 rounded-full {{ request('min_rating') == 4.5 ? 'active' : '' }}" onclick="setRating(4.5)">4.5+</span>
              <span class="filter-chip border border-gray-200 text-sm px-3 py-1.5 rounded-full {{ request('min_rating') == 4.9 ? 'active' : '' }}" onclick="setRating(4.9)">4.9+</span>
            </div>
          </div>

          <!-- Sort -->
          <div>
            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Sort By</h3>
            <select name="sort" id="sort-select" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand bg-white" onchange="this.form.submit()">
              <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recommended</option>
              <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
              <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
              <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
            </select>
          </div>

          <a href="{{ route('properties.search') }}" class="block text-center w-full py-2.5 text-sm font-semibold text-brand border-2 border-brand rounded-xl hover:bg-rose-50 transition-colors">
            Reset All Filters
          </a>
        </form>
      </aside>

      <!-- Mobile overlay -->
      <div id="filter-overlay" class="fixed inset-0 bg-black/40 z-40 sm:hidden hidden" onclick="closeSidebar()"></div>

      <!-- MAIN CONTENT -->
      <main class="flex-1 min-w-0">
        <!-- Header Row -->
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Browse Properties</h1>
            <p class="text-sm text-gray-400 mt-0.5">Showing {{ $properties->total() }} properties</p>
          </div>
          <!-- Desktop sort -->
          <div class="hidden sm:flex items-center gap-3">
            <label class="text-sm text-gray-500">Sort:</label>
            <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand bg-white" onchange="document.getElementById('sort-select').value=this.value; document.getElementById('filter-form').submit()">
              <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recommended</option>
              <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low → High</option>
              <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High → Low</option>
              <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
            </select>
          </div>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
          @forelse($properties as $property)
            <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 cursor-pointer group flex flex-col h-full animate-fadeUp opacity-0 animate-fill-forwards" style="animation-delay: {{ $loop->index * 0.05 }}s;" onclick="window.location.href='{{ route('properties.show', $property->slug) }}'">
              <div class="relative overflow-hidden h-52">
                @if($property->primaryImage)
                  <img src="{{ $property->primaryImage->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                @else
                  <div class="w-full h-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-300">{{ $property->propertyType->name ?? 'Stay' }}</div>
                @endif
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full capitalize">{{ $property->propertyType->name ?? 'Stay' }}</span>
                @if($property->average_rating >= 4.9)
                   <span class="absolute top-3 right-3 bg-brand text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">🔥 Top</span>
                @endif
              </div>
              <div class="p-5 flex-1 flex flex-col">
                <div class="flex items-start justify-between gap-2 mb-1">
                  <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 flex-1">{{ $property->title }}</h3>
                  <span class="text-xs font-semibold text-gray-600 flex-shrink-0 flex items-center gap-0.5">
                    <span class="text-amber-400">★</span> {{ number_format($property->average_rating ?? 4.8, 1) }}
                  </span>
                </div>
                <p class="text-gray-400 text-xs flex items-center gap-1 mb-4 flex-1">
                  <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                  {{ $property->city }}, {{ $property->country }}
                </p>
                <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                  <div><span class="text-xl font-extrabold text-gray-900">${{ number_format($property->price_per_night) }}</span><span class="text-gray-400 text-xs"> /night</span></div>
                  <button class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-lg" onclick="event.stopPropagation(); window.location.href='{{ route('properties.show', $property->slug) }}'">Book Now</button>
                </div>
              </div>
            </div>
          @empty
            <div class="col-span-full py-24 text-center">
              <div class="text-5xl mb-4">🏡</div>
              <h3 class="text-xl font-bold text-gray-800 mb-2">No properties found</h3>
              <p class="text-gray-400 text-sm mb-6">Try adjusting your filters or search term.</p>
              <a href="{{ route('properties.search') }}" class="btn-primary text-white font-semibold px-6 py-3 rounded-xl inline-block shadow-sm">Reset Filters</a>
            </div>
          @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="mt-12 flex justify-center">
            {{ $properties->appends(request()->query())->links('pagination::tailwind') }}
        </div>
      </main>
    </div>
  </div>
@endsection

@push('scripts')
<script>
    function syncPriceSlider() {
        let minInput = document.getElementById('price-min');
        let maxInput = document.getElementById('price-max');
        let minVal = parseInt(minInput.value);
        let maxVal = parseInt(maxInput.value);

        if (minVal > maxVal) {
            let tmp = minVal; minVal = maxVal; maxVal = tmp;
        }

        document.getElementById('price-min-val').textContent = minVal;
        document.getElementById('price-max-val').textContent = maxVal;
    }

    function setType(type) {
        document.getElementById('type-input').value = type;
        document.getElementById('filter-form').submit();
    }

    function setRating(val) {
        document.getElementById('rating-input').value = val;
        document.getElementById('filter-form').submit();
    }

    // Mobile sidebar
    document.getElementById('filter-toggle').addEventListener('click', () => {
        document.getElementById('filter-sidebar').classList.add('sidebar-open');
        document.getElementById('filter-overlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
    
    document.getElementById('filter-close').addEventListener('click', closeSidebar);
    
    function closeSidebar() {
        document.getElementById('filter-sidebar').classList.remove('sidebar-open');
        document.getElementById('filter-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush
