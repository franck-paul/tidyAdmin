/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Find <hr …><p class="right modules">…</p>
  const plugin_config = document.querySelector('hr + p.right.modules');
  if (plugin_config) {
    const plugin_title = document.querySelector('#content h2');
    if (plugin_title) {
      // Move config link after h2
      plugin_title.insertAdjacentElement('afterend', plugin_config);
      // Float right
      plugin_config.style.float = 'right';
      // Margin inline start
      plugin_config.style.marginInlineStart = '1em';
      // Make settings link looks like a button
      const plugin_config_link = plugin_config.querySelector('a');
      if (plugin_config_link) {
        plugin_config_link.classList.remove('module-config');
        plugin_config_link.classList.add('button', 'clone');
      }
      // Remove last hr (was just before plugin settings link)
      const hr = document.querySelectorAll('#content > hr');
      if (hr) {
        hr[hr.length - 1].remove();
      }
    }
  }
});
