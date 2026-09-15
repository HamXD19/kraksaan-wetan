/**
 * Custom Scroll Reveal Directive & Observer Engine
 * Specifically designed for public pages (excludes admin panel).
 */

const observerOptions = {
  root: null,
  rootMargin: '0px 0px -50px 0px', // Triggers slightly before reaching bottom of viewport
  threshold: 0.08
};

let sharedObserver = null;

function getSharedObserver() {
  if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
    return null;
  }

  if (!sharedObserver) {
    sharedObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          el.classList.add('is-revealed');
          // Once revealed, unobserve to free resources and keep state stable
          sharedObserver.unobserve(el);
        }
      });
    }, observerOptions);
  }

  return sharedObserver;
}

/**
 * Vue Directive: v-reveal
 * Usage:
 *   v-reveal
 *   v-reveal.scale
 *   v-reveal.left
 *   v-reveal.right
 *   v-reveal.fade
 *   v-reveal="{ delay: 200 }"
 */
export const vReveal = {
  mounted(el, binding) {
    // Check if current route is admin
    if (window.location.pathname.startsWith('/admin')) {
      el.classList.add('is-revealed');
      return;
    }

    const observer = getSharedObserver();
    if (!observer) {
      el.classList.add('is-revealed');
      return;
    }

    // Determine animation class from modifiers
    if (binding.modifiers.fade) {
      el.classList.add('reveal-fade');
    } else if (binding.modifiers.scale) {
      el.classList.add('reveal-scale');
    } else if (binding.modifiers.left) {
      el.classList.add('reveal-left');
    } else if (binding.modifiers.right) {
      el.classList.add('reveal-right');
    } else {
      el.classList.add('reveal');
    }

    // Support custom delay through value (e.g. v-reveal="{ delay: 150 }") or integer v-reveal="200"
    if (typeof binding.value === 'number') {
      el.style.transitionDelay = `${binding.value}ms`;
    } else if (binding.value && typeof binding.value === 'object' && binding.value.delay) {
      el.style.transitionDelay = `${binding.value.delay}ms`;
    }

    observer.observe(el);
  },

  unmounted(el) {
    if (sharedObserver) {
      sharedObserver.unobserve(el);
    }
  }
};

/**
 * Helper to scan and observe all `.reveal*` elements in DOM on page navigation
 */
export function scanAndObserveElements(rootEl = document) {
  if (typeof window === 'undefined' || window.location.pathname.startsWith('/admin')) {
    return;
  }

  const observer = getSharedObserver();
  if (!observer) return;

  const elements = rootEl.querySelectorAll('.reveal, .reveal-fade, .reveal-scale, .reveal-left, .reveal-right');
  elements.forEach((el) => {
    if (!el.classList.contains('is-revealed')) {
      observer.observe(el);
    }
  });
}
