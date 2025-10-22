/*global dotclear */
'use strict';

dotclear.ready(() => {
  const autohide = document.querySelector('#dock.autohide');
  if (autohide) {
    autohide.style.opacity = '0';
    const trigger = document.querySelector('#dock_hover');
    trigger.addEventListener('mouseover', (event) => {
      if (event.target.parentElement.style.opacity === '0') {
        event.target.parentElement.style.opacity = '1';
      }
    });
    autohide.addEventListener('mouseleave', (event) => {
      if (event.target.style.opacity === '1') {
        event.target.style.opacity = '0';
      }
    });
  }
});
