<!-- Site Header (Brand variant) -->
<header class="site-header" id="siteHeader">
    <div class="logo-wrap local-scroll brand-logo-block">
        <a href="index.php" class="logo">
            <img class="logoimg" src="<?= htmlspecialchars($brand['imageno'] ?? 'images/logo.png') ?>" alt="<?= htmlspecialchars($brand['brandname'] ?? 'SGIPL') ?>" />
        </a>
        <!-- <span class="brand-tagline"><?= ($brand['flag'] ?? 1) == 1 ? 'Authorised Brand Partner' : 'We Also Deal' ?></span> -->
    </div>

    <!-- Product filter bar: always visible, scrolls horizontally on narrow screens -->
    <nav class="nav-menu" id="navMenu">
        <a href="javascript:void(0)" class="nav-filter active" data-filter="all">Products</a>

        <?php if (!empty($seriesList)): ?>
        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-dropdown-toggle" data-dropdown="seriesDropdown">Series <span class="dropdown-caret">&#9662;</span></a>
        </div>
        <?php endif; ?>

        <a href="javascript:void(0)" class="nav-filter" data-filter="new_launch">New Launches</a>

        <?php if (!empty($brand['links']) || !empty($brand['website'])): ?>
        <div class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-dropdown-toggle" data-dropdown="moreDropdown">More <span class="dropdown-caret">&#9662;</span></a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="brand-search">
        <svg class="brand-search-icon" width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="7" cy="7" r="5.5" stroke="#8a8a8a" stroke-width="1.5"/>
            <path d="M11.3 11.3L15 15" stroke="#8a8a8a" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        <input type="text" class="brand-search-input" id="brandSearchInput" autocomplete="off">
        <span class="brand-search-placeholder" id="brandSearchPlaceholder">Search <strong>products</strong></span>
    </div>

    <?php if (!empty($seriesList)): ?>
    <div class="nav-dropdown-menu" id="seriesDropdown">
        <?php foreach ($seriesList as $s): ?>
            <a href="javascript:void(0)" class="nav-dropdown-item nav-filter" data-filter="series" data-series="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($brand['links']) || !empty($brand['website'])): ?>
    <div class="nav-dropdown-menu" id="moreDropdown">
        <?php if (!empty($brand['links'])): ?>
            <a href="<?= htmlspecialchars($brand['links']) ?>" target="_blank" rel="noopener" class="nav-dropdown-item">Download Catalogue</a>
        <?php endif; ?>
        <?php if (!empty($brand['website'])): ?>
            <a href="<?= htmlspecialchars($brand['website']) ?>" target="_blank" rel="noopener" class="nav-dropdown-item">About Brand</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</header>

<style>
.site-header {
    justify-content: flex-start;
}

/* Logo + tagline */
.brand-logo-block {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
}
.brand-logo-block .logoimg { display: block; }
.brand-tagline {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #8a8a8a;
}

/* Product filter bar */
.nav-menu a.nav-filter,
.nav-menu .nav-dropdown-toggle {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: .01em;
    text-transform: uppercase;
    color: #1a1a1a;
}
.nav-menu a.nav-filter:hover,
.nav-menu .nav-dropdown-toggle:hover,
.nav-menu a.nav-filter.active {
    color: #0d2b55;
}
.nav-menu {
    gap: 26px;
    margin-left: 48px;
    margin-right: 24px;
}

/* Search bar */
.brand-search {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1 1 320px;
    max-width: 460px;
    height: 46px;
    margin-left: 0;
    padding: 0 18px;
    background: #f2f3f5;
    border-radius: 999px;
}
.brand-search-icon { flex: 0 0 auto; }
.brand-search-input {
    position: relative;
    z-index: 1;
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 15px;
    color: #1a1a1a;
    min-width: 0;
}
.brand-search-placeholder {
    position: absolute;
    left: 44px;
    font-size: 15px;
    font-weight: 500;
    color: #8a8a8a;
    pointer-events: none;
}
.brand-search-placeholder strong { color: #1a1a1a; font-weight: 700; }
.brand-search.has-value .brand-search-placeholder { display: none; }
.nav-dropdown { display: flex; align-items: center; }
.nav-dropdown-toggle {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
}
.dropdown-caret { font-size: 10px; transition: transform .2s ease; }
.nav-dropdown-toggle.open .dropdown-caret { transform: rotate(180deg); }

/* Dropdown panels are positioned via JS (fixed, anchored under their toggle)
   so they're never clipped by the horizontally-scrolling nav bar on mobile. */
.nav-dropdown-menu {
    display: none;
    position: fixed;
    min-width: 190px;
    max-width: 260px;
    max-height: 320px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    padding: 6px;
    z-index: 999;
}
.nav-dropdown-menu.show { display: block; }

.nav-dropdown-menu a.nav-dropdown-item {
    display: block;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 500;
    color: #333;
    text-decoration: none;
    border-radius: 6px;
    white-space: nowrap;
}
.nav-dropdown-menu a.nav-dropdown-item:hover { background: #fdf6dc; color: #0D2B55; }
.nav-dropdown-menu a.nav-dropdown-item.active { background: #fdf6dc; color: #d4af37; font-weight: 600; }

@media (max-width: 860px) {
    .site-header {
        flex-wrap: wrap;
        height: auto;
        padding: 14px 16px 0;
        row-gap: 10px;
    }
    .nav-menu {
        order: 3;
        flex: 0 0 100%;
        width: 100%;
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        gap: 6px;
        margin: 0 -16px;
        padding: 10px 16px 14px;
        border-top: 1px solid #f0f0f0;
    }
    .nav-menu::-webkit-scrollbar { display: none; }
    .nav-menu a,
    .nav-menu .nav-dropdown-toggle,
    .nav-menu .nav-dropdown {
        flex: 0 0 auto;
        white-space: nowrap;
    }
    .brand-search {
        order: 4;
        flex: 0 0 100%;
        width: 100%;
        min-width: 0;
        margin-left: 0;
        margin-bottom: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var filterTriggers = document.querySelectorAll('.nav-filter');
    var dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');

    function closeAllDropdowns() {
        document.querySelectorAll('.nav-dropdown-menu.show').forEach(function(m) { m.classList.remove('show'); });
        document.querySelectorAll('.nav-dropdown-toggle.open').forEach(function(t) { t.classList.remove('open'); });
    }

    function applyBrandFilter(type, series) {
        var items = document.querySelectorAll('.product-item');
        var visibleCount = 0;
        items.forEach(function(item) {
            var show = true;
            if (type === 'new_launch') {
                show = item.getAttribute('data-new-launch') === '1';
            } else if (type === 'series') {
                show = item.getAttribute('data-series') === series;
            }
            item.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        var noResults = document.getElementById('noFilterResults');
        if (noResults) noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var menu = document.getElementById(this.dataset.dropdown);
            var isOpen = menu.classList.contains('show');
            closeAllDropdowns();
            if (!isOpen) {
                var rect = this.getBoundingClientRect();
                var menuWidth = Math.min(260, Math.max(190, window.innerWidth - 32));
                var left = Math.min(rect.left, window.innerWidth - menuWidth - 16);
                left = Math.max(16, left);
                menu.style.top = (rect.bottom + 8) + 'px';
                menu.style.left = left + 'px';
                menu.classList.add('show');
                this.classList.add('open');
            }
        });
    });

    document.addEventListener('click', closeAllDropdowns);
    window.addEventListener('scroll', closeAllDropdowns, { passive: true });
    window.addEventListener('resize', closeAllDropdowns);

    filterTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            var type = this.dataset.filter;
            var series = this.dataset.series || null;

            filterTriggers.forEach(function(t) { t.classList.remove('active'); });
            filterTriggers.forEach(function(t) {
                if (t.dataset.filter === type && (type !== 'series' || t.dataset.series === series)) {
                    t.classList.add('active');
                }
            });

            applyBrandFilter(type, series);
            closeAllDropdowns();
        });
    });

    var searchInput = document.getElementById('brandSearchInput');
    var searchWrap = document.querySelector('.brand-search');
    if (searchInput && searchWrap) {
        searchInput.addEventListener('input', function() {
            var q = this.value.trim().toLowerCase();
            searchWrap.classList.toggle('has-value', this.value.length > 0);

            if (!q) {
                var allTrigger = document.querySelector('.nav-filter[data-filter="all"]');
                if (allTrigger) allTrigger.click();
                return;
            }

            filterTriggers.forEach(function(t) { t.classList.remove('active'); });
            var items = document.querySelectorAll('.product-item');
            var visibleCount = 0;
            items.forEach(function(item) {
                var show = (item.getAttribute('data-name') || '').toLowerCase().indexOf(q) !== -1;
                item.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });
            var noResults = document.getElementById('noFilterResults');
            if (noResults) noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        });
        searchInput.addEventListener('focus', function() { searchWrap.classList.add('has-value'); });
        searchInput.addEventListener('blur', function() {
            if (!this.value) searchWrap.classList.remove('has-value');
        });
    }
});
</script>
