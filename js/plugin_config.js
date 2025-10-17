/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Find <hr …><p class="right modules">…</p>
  let plugin_config = document.querySelector('hr + p.right.modules');
  if (!plugin_config) plugin_config = document.querySelector('p.right.modules.vertical-separator'); // DC 2.36+
  if (plugin_config) {
    const plugin_title = document.querySelector('#content h2');
    if (plugin_title) {
      const next_sibling = plugin_title.nextElementSibling;
      // Move config link after h2
      plugin_title.insertAdjacentElement('afterend', plugin_config);
      // Remove vertical-separator class
      plugin_config.classList.remove('vertical-separator');
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
      // If the next sibling is a .top-add/.new-stuff or an alert (success, warning, …) add a right margin to it to make room for config button
      if (
        next_sibling.classList.contains('top-add') ||
        next_sibling.classList.contains('new-stuff') ||
        next_sibling.getAttribute('role') === 'alert'
      ) {
        next_sibling.classList.add('mini-config');
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
      // Replace some labels by icons
      for (const plugin_config_element of plugin_config.children) {
        if (
          plugin_config_element.href.includes('&conf=1') || // config page
          plugin_config_element.href.includes('process=UserPreferences') || // user pref page
          plugin_config_element.href.includes('process=BlogPref') // blog parameters
        ) {
          plugin_config_element.setAttribute('title', plugin_config_element.innerText);
          plugin_config_element.innerText = '';
          let icons = '';
          if (plugin_config_element.href.includes('process=UserPreferences')) {
            icons = '<img src="images/menu/user-pref.svg" alt="">';
          } else if (plugin_config_element.href.includes('process=BlogPref')) {
            icons =
              '<img src="images/menu/blog-pref.svg" alt="" class="light-only"><img src="images/menu/blog-pref-dark.svg" alt="" class="dark-only">';
          }
          if (icons === '')
            icons =
              '<img src="images/menu/settings.svg" alt="" class="light-only"><img src="images/menu/settings-dark.svg" alt="" class="dark-only">';
          plugin_config_element.append(...dotclear.htmlToNodes(icons));
          plugin_config_element.classList.add('mini-config');
          plugin_config_element.classList.remove('clone');
        }
      }
      // Remove last hr (was just before plugin settings link)
      const hr = document.querySelectorAll('#content > hr');
      if (hr.length) {
        hr[hr.length - 1].remove();
      }
    }
  }
});
