/*global dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('tidyadmin_userprefs');
  const isMotionReduced = window.matchMedia(`(prefers-reduced-motion: reduce)`)?.matches === true;

  // Find 1st focusable element
  const isTabbable = (el) => {
    // tabindex="-1" is never tabbable
    const { tabIndex } = el;
    if (tabIndex < 0) return false;

    // inert (self or ancestor)
    if (el.closest('[inert]')) return false;

    // disabled controls
    if (el.disabled) return false;

    // hidden attribute
    if (el.hidden) return false;

    // aria-hidden
    if (el.getAttribute('aria-hidden') === 'true') return false;

    // visibility
    const style = getComputedStyle(el);
    if (style.display === 'none' || style.visibility === 'hidden') {
      return false;
    }

    // layout presence
    const rect = el.getBoundingClientRect();
    if (rect.width === 0 && rect.height === 0) return false;

    return true;
  };

  const compareTabOrder = (a, b) => {
    const aIndex = a.tabIndex;
    const bIndex = b.tabIndex;

    // Both have positive tabindex → smallest first
    if (aIndex > 0 && bIndex > 0) {
      return aIndex - bIndex;
    }

    // One positive, one not → positive first
    if (aIndex > 0) return -1;
    if (bIndex > 0) return 1;

    // Both 0 (or no tabindex) → DOM order
    return a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1;
  };

  const focusFirstFocusableInBlock = (block) => {
    if (!block || block.closest('[inert]')) return;

    const focusableSelectors = [
      'a[href]',
      'button:not([disabled])',
      'input:not([disabled]):not([type="hidden"])',
      'select:not([disabled])',
      'textarea:not([disabled])',
      '[tabindex]',
    ].join(',');

    const candidates = Array.from(block.querySelectorAll(focusableSelectors));
    const tabbables = candidates.filter(isTabbable);
    if (!tabbables.length) return;

    tabbables.sort(compareTabOrder);
    tabbables[0].focus({
      preventScroll: true,
      focusVisible: true,
    });
  };

  const move = (selection, tab = '') => {
    const prefix = tab === '' ? '#' : `#${tab}.`;
    globalThis.location = `${prefix}${selection}`;
    const block = document.getElementById(selection);
    // Scroll to the block
    block.scrollIntoView({
      behavior: isMotionReduced ? 'instant' : 'smooth',
      block: 'start',
      inline: 'nearest',
    });
    // Give focus to the 1st focusable element of block, if possible
    focusFirstFocusableInBlock(block);
  };

  for (const div of document.querySelectorAll('[name="user-options"]')) {
    const title = div.querySelector('h3');
    if (title) {
      const options = [];
      // Search for fieldsets
      for (const fieldset of div.querySelectorAll('fieldset')) {
        let id = fieldset.getAttribute('id');
        const legend = fieldset.querySelector('legend');
        if (legend) {
          if (!id) {
            // Add a random ID to the fieldset
            id = `group-${Date.now()}-${Math.floor(Math.random() * 8999 + 1000)}`;
            fieldset.setAttribute('id', id);
          }
          const option = document.createElement('option');
          option.setAttribute('value', `${id}`);
          option.appendChild(document.createTextNode(legend.textContent.trim()));
          options.push(option);
        }
      }
      if (options.length) {
        const label = document.createElement('label');
        label.appendChild(document.createTextNode(data.goto));
        label.classList.add('navigation', 'form-buttons');
        const select = document.createElement('select');
        select.setAttribute('id', `go-${div.getAttribute('name')}`);
        select.classList.add('meta-helper'); // meta-helper class will force confirm-close to ignore this select changes
        options.forEach((option) => select.appendChild(option));
        select.addEventListener('change', (event) => move(event.target.value, 'user-options'));
        label.appendChild(select);
        title.after(label);
      }
    }
  }
});
