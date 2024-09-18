/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Clone media search form input to visible area
  const search_media = document.querySelector('#filters-form #q')?.parentNode;
  if (search_media) {
    const recent_media = document.querySelector('p.media-recent');
    const filters = document.querySelector('#filter-details');
    if (recent_media || filters) {
      // Clone search input (and its label)
      const cloned = search_media.cloneNode(true);
      if (cloned) {
        const cloned_input = cloned.querySelector('#q');
        if (cloned_input) {
          cloned_input.setAttribute('id', 'qq');
          cloned_input.setAttribute('name', 'qq');
        }
        const cloned_label = cloned.querySelector('label');
        if (cloned_label) {
          cloned_label.setAttribute('for', 'qq');
        }
        // Insert cloned input and its label before filters
        if (recent_media) {
          // Next to recent folders selector
          recent_media.insertAdjacentElement('afterend', cloned);
          cloned.classList.add('tidy_moved');
          recent_media.classList.add('tidy_moved');
        } else {
          filters.insertAdjacentElement('beforebegin', cloned);
        }
        // Simulate form submit on enter key in input cloned field
        const filtersform = document.getElementById('filters-form');
        if (filtersform) {
          cloned_input.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
              search_media.querySelector('#q')?.setAttribute('value', cloned_input.value);
              filtersform.submit();
            }
          });
        }
      }
    }
  }
});
