/*global dotclear */
'use strict';

dotclear.ready(() => {
  document.getElementById('header')?.classList.add('tidy_dcicon');
  document.querySelector('body.popup')?.classList.add('tidy_dcicon');
  const title = document.querySelector('body.popup h1');
  if (title) {
    title.setAttribute('title', title.textContent);
    title.textContent = '';
  }
});
