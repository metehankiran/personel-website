/* Metehan Kıran — Site JS
   Theme toggle + filter chips + dropdown (CSS handles dropdown hover)
   ────────────────────────────────────────────────── */

(function () {
  // ─── Theme ─────────────────────────────────────────
  var THEME_KEY = 'mk-theme';
  var root = document.documentElement;
  var mq = window.matchMedia('(prefers-color-scheme: dark)');

  var activeClass = 'bg-white dark:bg-neutral-800 text-neutral-950 dark:text-neutral-50 shadow-sm';
  var inactiveClass = 'text-neutral-400 dark:text-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300';

  function resolveTheme(mode) {
    if (mode === 'system') return mq.matches ? 'dark' : 'light';
    return mode;
  }

  function applyTheme(mode) {
    root.setAttribute('data-theme', resolveTheme(mode));
    document.querySelectorAll('[data-theme-set]').forEach(function (btn) {
      var isActive = btn.getAttribute('data-theme-set') === mode;
      activeClass.split(' ').forEach(function (c) { btn.classList.toggle(c, isActive); });
      inactiveClass.split(' ').forEach(function (c) { btn.classList.toggle(c, !isActive); });
    });
  }

  var stored = null;
  try { stored = localStorage.getItem(THEME_KEY); } catch (e) {}
  var currentMode = stored || 'system';
  applyTheme(currentMode);

  mq.addEventListener('change', function () {
    var mode = null;
    try { mode = localStorage.getItem(THEME_KEY); } catch (e) {}
    if (!mode || mode === 'system') applyTheme('system');
  });

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-theme-set]');
    if (!btn) return;
    var mode = btn.getAttribute('data-theme-set');
    currentMode = mode;
    applyTheme(mode);
    try { localStorage.setItem(THEME_KEY, mode); } catch (err) {}
  });

  // ─── Filter chips ──────────────────────────────────
  document.addEventListener('click', function (e) {
    var chip = e.target.closest('[data-filter]');
    if (!chip) return;
    var group = chip.closest('[data-filter-group]');
    if (!group) return;
    var name = group.getAttribute('data-filter-group');
    var value = chip.getAttribute('data-filter');

    group.querySelectorAll('[data-filter]').forEach(function (c) { c.classList.remove('active'); });
    chip.classList.add('active');

    document.querySelectorAll('[data-filter-target="' + name + '"]').forEach(function (item) {
      var v = item.getAttribute('data-filter-value');
      var visible = value === 'all' || v === value;
      item.classList.toggle('is-hidden', !visible);
    });
  });

  // ─── Form submission (placeholder — Laravel will wire this) ──
  document.addEventListener('submit', function (e) {
    if (e.target.matches('[data-mk-form]')) {
      e.preventDefault();
      alert('Mesajın iletildi (demo). Laravel backend\'e bağlandığında çalışacak.');
    }
  });

  // ─── Mark active nav by current pathname ──────────
  var path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('[data-nav]').forEach(function (el) {
    if (el.getAttribute('data-nav') === path) {
      el.classList.add('active');
      var group = el.closest('.nav-item');
      if (group) {
        var trigger = group.querySelector('.nav-link');
        if (trigger && trigger !== el) trigger.classList.add('active');
      }
    }
  });
})();

/* ─── Custom select (x-select Blade component) ───────────
   Keyboard and ARIA behaviour follows the WAI-ARIA select-only combobox pattern:
   focus stays on the button, the highlighted option is exposed via aria-activedescendant. */
document.addEventListener('alpine:init', function () {
  Alpine.data('mkSelect', function (config) {
    return {
      value: config.value || '',
      options: config.options || [],
      placeholder: config.placeholder || '',
      open: false,
      activeIndex: -1,
      typed: '',
      typedTimer: null,

      get selectedIndex() {
        var current = String(this.value == null ? '' : this.value);
        return this.options.findIndex(function (option) { return option.value === current; });
      },

      get selectedLabel() {
        var option = this.options[this.selectedIndex];
        return option ? option.label : '';
      },

      toggle: function () { this.open ? this.close() : this.show(); },

      show: function () {
        this.open = true;
        this.activeIndex = this.selectedIndex >= 0 ? this.selectedIndex : 0;
        this.$nextTick(this.scrollToActive.bind(this));
      },

      close: function (focusButton) {
        if (!this.open) return;
        this.open = false;
        if (focusButton) this.$refs.button.focus();
      },

      choose: function (index) {
        var option = this.options[index];
        if (!option) return;
        this.value = option.value;
        this.close(true);
      },

      setActive: function (index) {
        this.activeIndex = Math.max(0, Math.min(this.options.length - 1, index));
        this.scrollToActive();
      },

      scrollToActive: function () {
        var item = this.$refs.listbox.children[this.activeIndex];
        if (item) item.scrollIntoView({ block: 'nearest' });
      },

      typeahead: function (character) {
        var self = this;
        clearTimeout(this.typedTimer);
        this.typed += character.toLocaleLowerCase('tr');
        this.typedTimer = setTimeout(function () { self.typed = ''; }, 600);

        var match = this.options.findIndex(function (option) {
          return option.label.toLocaleLowerCase('tr').indexOf(self.typed) === 0;
        });
        if (match < 0) return;
        if (!this.open) this.show();
        this.setActive(match);
      },

      onButtonKeydown: function (event) {
        var key = event.key;

        if (key === 'Escape') {
          // Only swallow Escape while the list is open, so an enclosing modal can still close.
          if (this.open) { event.stopPropagation(); this.close(true); }
          return;
        }
        if (key === 'Tab') { this.close(); return; }

        if (key === 'ArrowDown' || key === 'ArrowUp') {
          event.preventDefault();
          if (!this.open) { this.show(); return; }
          this.setActive(this.activeIndex + (key === 'ArrowDown' ? 1 : -1));
          return;
        }
        if (key === 'Home' || key === 'End') {
          if (!this.open) return;
          event.preventDefault();
          this.setActive(key === 'Home' ? 0 : this.options.length - 1);
          return;
        }
        if (key === 'Enter' || key === ' ') {
          event.preventDefault();
          this.open ? this.choose(this.activeIndex) : this.show();
          return;
        }
        if (key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
          this.typeahead(key);
        }
      }
    };
  });
});
