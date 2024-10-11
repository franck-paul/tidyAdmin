/*global dotclear */
'use strict';

dotclear.ready(() => {
  Object.assign(dotclear, dotclear.getData('tidyadmin'));
  if (typeof dotclear.header_color !== 'undefined') {
    document.documentElement.style.setProperty('--tidy-header-background', dotclear.header_color);
  }
});
