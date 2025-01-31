/*global dotclear */
'use strict';

dotclear.ready(() => {
  const media_alt = document.querySelector('#change-properties-form #media_alt');
  if (!media_alt) {
    return;
  }
  const media_desc = document.querySelector('#change-properties-form #media_desc');
  if (!media_desc) {
    return;
  }
  // The two fields are present, add a switch button
  const button = document.createElement('button');
  button.setAttribute('type', 'button');
  button.setAttribute('title', 'Swap alternate text and description');
  button.classList.add('tidy_swap');
  button.innerHTML =
    '<svg height="24" viewBox="0 0 48 48" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m0 0h48v48h-48z" fill="none"/><path d="m24.4 33.5a2.1 2.1 0 0 0 .2-2.7 1.9 1.9 0 0 0 -3-.2l-4.6 4.6v-27.2a2 2 0 0 0 -4 0v27.2l-4.6-4.6a1.9 1.9 0 0 0 -3 .2 2.1 2.1 0 0 0 .2 2.7l8 7.9a1.9 1.9 0 0 0 2.8 0z" fill="currentColor"/><path d="m23.6 14.5a2.1 2.1 0 0 0 -.2 2.7 1.9 1.9 0 0 0 3 .2l4.6-4.6v27.2a2 2 0 0 0 4 0v-27.2l4.6 4.6a1.9 1.9 0 0 0 3-.2 2.1 2.1 0 0 0 -.2-2.7l-8-7.9a1.9 1.9 0 0 0 -2.8 0z" fill="currentColor"/></svg>';
  // Insert button between two fields
  media_alt.parentNode?.after(button);
  // Cope with click event
  button.addEventListener('click', () => {
    // Swap two fields contents
    [media_alt.value, media_desc.value] = [media_desc.value, media_alt.value];
  });
});
