/*global dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('tidyadmin_userprefs');

  const move = (selection, tab = '') => {
    const prefix = tab === '' ? '#' : `#${tab}.`;
    globalThis.location = `${prefix}${selection}`;
    const block = document.getElementById(selection);
    // Scroll to the block
    block.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
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
        for (const option of options) {
          select.appendChild(option);
        }
        select.addEventListener('change', (event) => move(event.target.value, 'user-options'));
        label.appendChild(select);
        title.after(label);
      }
    }
  }
});
