/**
 * StayNest API Client
 * Centralised fetch wrapper for all Laravel API calls.
 * Base URL auto-detects current origin so it works on any environment.
 */

const API = (() => {
  const BASE = '/api';          // Same-origin — no CORS issues
  const DEFAULT_HEADERS = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',   // Laravel detects AJAX
  };

  /* ─── CSRF helper (reads Laravel's XSRF-TOKEN cookie) ─── */
  function getCsrfToken() {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : null;
  }

  /* ─── Core fetch wrapper ─── */
  async function request(method, path, body = null) {
    const headers = { ...DEFAULT_HEADERS };
    const csrf = getCsrfToken();
    if (csrf) headers['X-XSRF-TOKEN'] = csrf;

    const options = { method, headers };
    if (body) options.body = JSON.stringify(body);

    const res = await fetch(`${BASE}${path}`, options);

    // Graceful error handling — parse JSON even on error responses
    const data = await res.json().catch(() => ({ message: 'Invalid server response' }));

    if (!res.ok) {
      const err = new Error(data.message || `HTTP ${res.status}`);
      err.status = res.status;
      err.errors = data.errors || {};   // Laravel validation errors
      throw err;
    }

    return data;
  }

  /* ─── Public API ─── */
  return {
    /* Properties */
    getProperties(params = {}) {
      const qs = new URLSearchParams(params).toString();
      return request('GET', `/properties${qs ? '?' + qs : ''}`);
    },

    getProperty(id) {
      return request('GET', `/properties/${id}`);
    },

    /* Availability */
    getAvailability(propertyId, startDate, endDate) {
      const qs = new URLSearchParams({ start_date: startDate, end_date: endDate }).toString();
      return request('GET', `/v1/properties/${propertyId}/availability?${qs}`);
    },

    /* Bookings */
    createBooking(payload) {
      return request('POST', '/bookings', payload);
    },
  };
})();


/* ═══════════════════════════════════════════════════════════
   UI HELPERS — shared across all pages
═══════════════════════════════════════════════════════════ */

const UI = {
  /* Generic skeleton card */
  skeletonCard: () => `
    <div class="bg-white rounded-2xl overflow-hidden shadow-md animate-pulse">
      <div class="h-52 bg-gray-200"></div>
      <div class="p-5 space-y-3">
        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
        <div class="h-3 bg-gray-200 rounded w-1/3"></div>
        <div class="flex justify-between mt-4">
          <div class="h-5 bg-gray-200 rounded w-1/4"></div>
          <div class="h-8 bg-gray-200 rounded-lg w-1/4"></div>
        </div>
      </div>
    </div>`,

  /* Render N skeletons */
  showSkeletons(container, count = 6) {
    if (!container) return;
    container.innerHTML = Array(count).fill(this.skeletonCard()).join('');
  },

  /* Error banner */
  showError(container, message, onRetry = null) {
    if (!container) return;
    container.innerHTML = `
      <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
        <div class="text-5xl mb-4">😕</div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Something went wrong</h3>
        <p class="text-gray-400 text-sm mb-6 max-w-sm">${message}</p>
        ${onRetry ? `<button onclick="(${onRetry.toString()})()" class="bg-brand text-white font-semibold px-6 py-3 rounded-xl hover:opacity-90 transition">Try again</button>` : ''}
      </div>`;
  },

  /* Toast notification */
  toast(message, type = 'success', duration = 3000) {
    const existing = document.getElementById('sn-toast');
    if (existing) existing.remove();

    const colors = { success: 'bg-gray-900', error: 'bg-red-600', warning: 'bg-amber-500' };
    const icons  = { success: '✓', error: '✕', warning: '!' };

    const el = document.createElement('div');
    el.id = 'sn-toast';
    el.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] ${colors[type]} text-white text-sm font-semibold px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-2 transition-all duration-300 translate-y-16 opacity-0`;
    el.innerHTML = `<span class="w-5 h-5 rounded-full border-2 border-white/60 flex items-center justify-center text-xs">${icons[type]}</span> ${message}`;
    document.body.appendChild(el);

    requestAnimationFrame(() => {
      el.classList.remove('translate-y-16', 'opacity-0');
    });
    setTimeout(() => {
      el.classList.add('translate-y-16', 'opacity-0');
      setTimeout(() => el.remove(), 350);
    }, duration);
  },

  /* Render a single property card HTML */
  propertyCard(p) {
    const badge = p.badge ? `<span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">${p.badge}</span>` : '';
    const img = p.thumbnail || p.image_url || '/property_apartment.png';
    const rating = parseFloat(p.rating || p.average_rating || 4.8).toFixed(2);
    const type = p.type || p.property_type || 'property';
    const loc = p.location || p.city || p.address || '—';
    const price = parseFloat(p.price || p.price_per_night || 0).toFixed(0);

    return `
      <article class="card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group"
               onclick="window.location.href='/property-detail.html?id=${p.id}'">
        <div class="relative overflow-hidden h-52">
          <img src="${img}" alt="${p.title || p.name}"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 bg-gray-100"
               onerror="this.src='/property_apartment.png'"
               loading="lazy" />
          <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full capitalize">${type}</span>
          ${badge}
        </div>
        <div class="p-5">
          <div class="flex items-start justify-between gap-2 mb-1">
            <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 flex-1">${p.title || p.name}</h3>
            <span class="text-xs font-semibold text-amber-500 flex-shrink-0">★ ${rating}</span>
          </div>
          <p class="text-gray-400 text-xs flex items-center gap-1 mb-4">📍 ${loc}</p>
          <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
            <div><span class="text-xl font-extrabold text-gray-900">$${price}</span><span class="text-gray-400 text-xs"> /night</span></div>
            <button class="bg-gradient-to-r from-red-500 to-red-700 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:shadow-lg hover:shadow-red-200 transition"
                    onclick="event.stopPropagation(); window.location.href='/booking.html?id=${p.id}'">
              Book Now
            </button>
          </div>
        </div>
      </article>`;
  },
};


/* ═══════════════════════════════════════════════════════════
   PROPERTY LISTING PAGE  (/properties.html)
   Call PropertiesPage.init() on that page's DOMContentLoaded.
═══════════════════════════════════════════════════════════ */

const PropertiesPage = {
  grid: null,
  empty: null,
  pagination: null,
  resultsCount: null,

  PER_PAGE: 6,
  currentPage: 1,
  totalItems: 0,
  allItems: [],   // cached from API

  filters: {
    type: 'all',
    priceMin: 0,
    priceMax: 1000,
    locations: [],
    rating: 0,
    query: '',
    sort: 'recommended',
  },

  init() {
    this.grid = document.getElementById('property-grid');
    this.empty = document.getElementById('empty-state');
    this.pagination = document.getElementById('pagination');
    this.resultsCount = document.getElementById('results-count');
    this.load();
  },

  async load() {
    UI.showSkeletons(this.grid, this.PER_PAGE);
    if (this.empty) this.empty.classList.add('hidden');
    if (this.pagination) this.pagination.innerHTML = '';

    try {
      const data = await API.getProperties();
      // Support both { data: [...] } and plain array responses
      this.allItems = Array.isArray(data) ? data : (data.data || []);
      this.currentPage = 1;
      this.render();
    } catch (err) {
      console.error('[PropertiesPage] load failed:', err);
      UI.showError(this.grid, err.message || 'Could not load properties. Check your connection.', () => PropertiesPage.load());
    }
  },

  getFiltered() {
    const f = this.filters;
    let list = this.allItems.filter(p => {
      const price = parseFloat(p.price || p.price_per_night || 0);
      const type  = (p.type || p.property_type || '').toLowerCase();
      const city  = (p.city || p.location || '').toLowerCase();
      const title = (p.title || p.name || '').toLowerCase();
      const rating = parseFloat(p.rating || p.average_rating || 0);

      if (f.type !== 'all' && !type.includes(f.type)) return false;
      if (price < f.priceMin || price > f.priceMax) return false;
      if (f.locations.length && !f.locations.some(l => city.includes(l))) return false;
      if (rating < f.rating) return false;
      if (f.query && !title.includes(f.query) && !city.includes(f.query)) return false;
      return true;
    });

    if (f.sort === 'price-asc')  list = list.sort((a, b) => (a.price_per_night||a.price||0) - (b.price_per_night||b.price||0));
    if (f.sort === 'price-desc') list = list.sort((a, b) => (b.price_per_night||b.price||0) - (a.price_per_night||a.price||0));
    if (f.sort === 'rating')     list = list.sort((a, b) => (b.rating||0) - (a.rating||0));
    return list;
  },

  render() {
    const filtered = this.getFiltered();
    const start = (this.currentPage - 1) * this.PER_PAGE;
    const page  = filtered.slice(start, start + this.PER_PAGE);

    if (this.resultsCount)
      this.resultsCount.textContent = `Showing ${page.length ? start + 1 : 0}–${start + page.length} of ${filtered.length} properties`;

    if (!page.length) {
      if (this.grid) this.grid.innerHTML = '';
      if (this.empty) this.empty.classList.remove('hidden');
      if (this.pagination) this.pagination.innerHTML = '';
      return;
    }
    if (this.empty) this.empty.classList.add('hidden');
    if (this.grid) this.grid.innerHTML = page.map(p => UI.propertyCard(p)).join('');
    if (this.pagination) this.pagination.innerHTML = this._paginationHtml(filtered.length);
  },

  applyFilters() {
    this.currentPage = 1;
    this.render();
  },

  setFilter(key, value) {
    this.filters[key] = value;
    this.applyFilters();
  },

  goPage(n) {
    const total = Math.ceil(this.getFiltered().length / this.PER_PAGE);
    if (n < 1 || n > total) return;
    this.currentPage = n;
    this.render();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  },

  _paginationHtml(total) {
    const totalPages = Math.ceil(total / this.PER_PAGE);
    if (totalPages <= 1) return '';
    const cur = this.currentPage;
    const base = 'page-btn border border-gray-200 rounded-xl w-10 h-10 flex items-center justify-center text-sm font-medium bg-white';
    let h = `<button class="${base}" onclick="PropertiesPage.goPage(${cur-1})" ${cur===1?'disabled':''}>‹</button>`;
    for (let i = 1; i <= totalPages; i++) {
      if (totalPages > 7 && i > 2 && i < totalPages - 1 && Math.abs(i - cur) > 1) {
        if (i === 3 || i === totalPages - 2) h += `<span class="text-gray-400 px-1">…</span>`;
        continue;
      }
      h += `<button class="${base}${i===cur?' active':''}" onclick="PropertiesPage.goPage(${i})">${i}</button>`;
    }
    h += `<button class="${base}" onclick="PropertiesPage.goPage(${cur+1})" ${cur===totalPages?'disabled':''}>›</button>`;
    return h;
  },
};


/* ═══════════════════════════════════════════════════════════
   PROPERTY DETAIL PAGE  (/property-detail.html)
   Call DetailPage.init() on DOMContentLoaded.
═══════════════════════════════════════════════════════════ */

const DetailPage = {
  async init() {
    const id = new URLSearchParams(location.search).get('id');
    if (!id) return;   // demo page with hardcoded data — skip

    try {
      const data = await API.getProperty(id);
      const p = data.data || data;
      this._populate(p);
      this._loadAvailability(id);
    } catch (err) {
      UI.toast('Could not load property details.', 'error');
      console.error('[DetailPage] init failed:', err);
    }
  },

  _populate(p) {
    document.title = `${p.title || p.name} — StayNest`;
    const el = id => document.getElementById(id);
    if (el('dp-title'))   el('dp-title').textContent   = p.title || p.name;
    if (el('dp-loc'))     el('dp-loc').textContent     = `📍 ${p.city || p.location || ''}`;
    if (el('dp-price'))   el('dp-price').textContent   = `$${parseFloat(p.price_per_night || p.price || 0).toFixed(0)}`;
    if (el('dp-rating'))  el('dp-rating').textContent  = `★ ${parseFloat(p.rating || 4.9).toFixed(1)}`;
    if (el('dp-desc'))    el('dp-desc').textContent    = p.description || '';
  },

  async _loadAvailability(id) {
    const now  = new Date();
    const next = new Date(); next.setMonth(next.getMonth() + 2);
    const fmt  = d => d.toISOString().split('T')[0];

    try {
      const res = await API.getAvailability(id, fmt(now), fmt(next));
      const unavailable = res.unavailable_dates || [];
      // Expose to calendar if it exists on this page
      if (typeof window.setUnavailableDates === 'function') {
        window.setUnavailableDates(unavailable);
      }
    } catch (err) {
      console.warn('[DetailPage] availability fetch failed:', err);
    }
  },
};


/* ═══════════════════════════════════════════════════════════
   BOOKING PAGE  (/booking.html)
   Call BookingPage.init() on DOMContentLoaded.
═══════════════════════════════════════════════════════════ */

const BookingPage = {
  propertyId: null,
  RATE: 380,
  CLEANING: 60,

  async init() {
    const params = new URLSearchParams(location.search);
    this.propertyId = params.get('id');

    if (this.propertyId) {
      await this._loadProperty();
      await this._loadAvailability();
    }

    // Wire submit button
    const btn = document.querySelector('[data-submit-booking]');
    if (btn) btn.addEventListener('click', () => this.submit());
  },

  async _loadProperty() {
    try {
      const data = await API.getProperty(this.propertyId);
      const p = data.data || data;
      this.RATE = parseFloat(p.price_per_night || p.price || this.RATE);

      // Update hero strip if present
      const nameEl = document.getElementById('booking-prop-name');
      const priceEl = document.getElementById('booking-prop-price');
      const imgEl  = document.getElementById('booking-prop-img');
      if (nameEl)  nameEl.textContent  = p.title || p.name;
      if (priceEl) priceEl.textContent = `$${this.RATE.toFixed(0)}`;
      if (imgEl)   imgEl.src = p.thumbnail || p.image_url || imgEl.src;
    } catch (err) {
      console.warn('[BookingPage] property load failed:', err);
    }
  },

  async _loadAvailability() {
    const now  = new Date();
    const next = new Date(); next.setMonth(next.getMonth() + 2);
    const fmt  = d => d.toISOString().split('T')[0];

    try {
      const res = await API.getAvailability(this.propertyId, fmt(now), fmt(next));
      const dates = (res.unavailable_dates || []).map(r => {
        // Support both string dates and {check_in_date, check_out_date} objects
        if (typeof r === 'string') return r;
        return null;
      }).filter(Boolean);

      if (typeof window.setUnavailableDates === 'function') {
        window.setUnavailableDates(dates);
      }
    } catch (err) {
      console.warn('[BookingPage] availability load failed:', err);
    }
  },

  async submit() {
    const checkin  = document.getElementById('card-checkin')?.value
                  || document.getElementById('m-checkin')?.value;
    const checkout = document.getElementById('card-checkout')?.value
                  || document.getElementById('m-checkout')?.value;

    if (!checkin || !checkout) {
      UI.toast('Please select check-in and check-out dates.', 'warning'); return;
    }
    if (!this.propertyId) {
      UI.toast('No property selected.', 'error'); return;
    }

    const payload = {
      property_id:    this.propertyId,
      check_in_date:  checkin,
      check_out_date: checkout,
      guests: {
        adults:   parseInt(document.getElementById('adults-num')?.textContent || 1),
        children: parseInt(document.getElementById('children-num')?.textContent || 0),
        infants:  parseInt(document.getElementById('infants-num')?.textContent || 0),
      },
    };

    const submitBtn = document.querySelector('[data-submit-booking]');
    const original  = submitBtn?.textContent;
    if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Processing…'; }

    try {
      const res = await API.createBooking(payload);
      UI.toast('🎉 Booking confirmed! Redirecting…', 'success', 3000);
      setTimeout(() => window.location.href = '/', 3000);
    } catch (err) {
      console.error('[BookingPage] submit failed:', err);

      if (err.status === 422) {
        // Laravel validation errors
        const msgs = Object.values(err.errors).flat().join(' · ');
        UI.toast(msgs || 'Please fix the form errors.', 'error', 4000);
      } else if (err.status === 409) {
        UI.toast('Those dates are no longer available. Please choose different dates.', 'error', 4000);
      } else if (err.status === 401) {
        UI.toast('Please sign in to book.', 'warning', 3000);
        setTimeout(() => window.location.href = '/login', 3000);
      } else {
        UI.toast(err.message || 'Booking failed. Please try again.', 'error');
      }
    } finally {
      if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = original; }
    }
  },
};
