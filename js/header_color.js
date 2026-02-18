/*global dotclear */
'use strict';

dotclear.ready(() => {
  Object.assign(dotclear, dotclear.getData('tidyadmin'));
  if (dotclear.header_background !== undefined && dotclear.header_color !== undefined) {
    document.documentElement.style.setProperty('--tidy-header-color', dotclear.header_color);
    document.documentElement.style.setProperty('--tidy-header-background', dotclear.header_background);
  }
});
