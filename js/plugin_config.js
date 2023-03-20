/*global $, dotclear */
'use strict';

$(() => {
  // Find <hr … /><p class="right modules">…</p>
  const $plugin_config = $('hr + p.right.modules');
  if ($plugin_config !== undefined) {
    const $plugin_title = $('#content h2');
    if ($plugin_title !== undefined) {
      // Move config link after h2
      $plugin_config.insertAfter($plugin_title);
      // Float right
      $plugin_config.css('float', 'right');
      const $plugin_config_link = $('a', $plugin_config);
      if ($plugin_config_link !== undefined) {
        $plugin_config_link.removeClass('module-config');
        $plugin_config_link.addClass(['button', 'clone']);
      }
      // Remove hr
      $('#content > hr:last-child')?.remove();
    }
  }
});
