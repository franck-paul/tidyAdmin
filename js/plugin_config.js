/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Find <hr …><p class="right modules">…</p>
  const plugin_config = document.querySelector('hr + p.right.modules');
  if (plugin_config) {
    const plugin_title = document.querySelector('#content h2');
    if (plugin_title) {
      const next_sibling = plugin_title.nextElementSibling;
      // Move config link after h2
      plugin_title.insertAdjacentElement('afterend', plugin_config);
      // Position
      plugin_config.style.position = 'absolute';
      plugin_config.style.right = '1em';
      // Z-index
      plugin_config.style.zIndex = '1';
      // If the first child of this form is a fieldset (or .fieldset class) add a padding to visually put the button inside the first fieldset
      if (
        next_sibling.tagName === 'FORM' &&
        (next_sibling.firstElementChild?.tagName === 'FIELDSET' ||
          next_sibling.firstElementChild?.classList.contains('fieldset'))
      ) {
        plugin_config.style.padding = '1em';
      }
      // Make settings links looks like a button
      const plugin_config_links = plugin_config.querySelectorAll('a');
      for (const plugin_config_link of plugin_config_links) {
        plugin_config_link.classList.remove('module-config');
        plugin_config_link.classList.add('button', 'clone');
      }
      // Remove not link elements
      plugin_config.classList.add('form-buttons');
      for (const plugin_config_element of plugin_config.childNodes) {
        if (plugin_config_element.tagName !== 'A') plugin_config_element.remove();
      }
      // Remove last hr (was just before plugin settings link)
      const hr = document.querySelectorAll('#content > hr');
      if (hr) {
        hr[hr.length - 1].remove();
      }
    }
  }
});
