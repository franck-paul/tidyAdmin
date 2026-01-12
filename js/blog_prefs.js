/*global dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('tidyadmin_blogprefs');

  const move = (selection, tab = '') => {
    const prefix = tab === '' ? '#' : `#${tab}.`;
    globalThis.location = `${prefix}${selection}`;
    const block = document.getElementById(selection);
    // Open the parent if necessary
    if (block.classList.contains('hide')) {
      const parent = block.parentElement;
      const button = parent.querySelector('h3 button');
      if (button) {
        button.click();
      }
    }
    // Scroll to the block
    block.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
  };

  for (const div of document.querySelectorAll('#standard-pref,#advanced-pref,#plugins-pref')) {
    const title = div.querySelector('h3');
    if (title) {
      const options = [];
      // Search for fieldsets
      for (const fieldset of div.querySelectorAll('fieldset')) {
        const id = fieldset.getAttribute('id');
        const legend = fieldset.querySelector('legend');
        if (id && legend) {
          const option = document.createElement('option');
          option.setAttribute('value', `${id}`);
          option.appendChild(document.createTextNode(legend.textContent));
          options.push(option);
        }
      }
      if (options.length) {
        const label = document.createElement('label');
        label.appendChild(document.createTextNode(data.goto));
        label.classList.add('navigation', 'form-buttons');
        const select = document.createElement('select');
        select.setAttribute('id', `go-${div.getAttribute('id')}`);
        select.classList.add('meta-helper'); // meta-helper class will force confirm-close to ignore this select changes
        for (const option of options) {
          select.appendChild(option);
        }
        select.addEventListener('change', (event) => move(event.target.value, 'params'));
        label.appendChild(select);
        title.after(label);
      }
    }
  }
});
