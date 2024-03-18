/*global $, dotclear */
'use strict';

$(() => {
  // double click on header switch current theme (only if dotclear.debug is not set)
  if (!dotclear.debug) {
    const header = document.querySelector('#header') ? document.querySelector('#header') : document.querySelector('h1');
    header?.addEventListener('dblclick', (_event) => {
      let { theme } = document.documentElement.dataset;
      if (theme == null || theme === '') {
        theme = window.matchMedia('(prefers-color-scheme: dark)') ? 'dark' : 'light';
      }
      // Set new theme, the application will be cope by the mutation observer (see above)
      document.documentElement.dataset.theme = theme === 'dark' ? 'light' : 'dark';
    });
  }
});
