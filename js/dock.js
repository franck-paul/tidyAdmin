/*global dotclear */
'use strict';

dotclear.ready(() => {
  const dock = document.querySelector('#dock');
  if (!dock) {
    return;
  }

  const dockRect = dock.getBoundingClientRect();
  const targets = [...document.getElementsByClassName('jstElements')];

  const callback = (entries, _observer) => {
    for (const entry of entries) {
      const doesOverlap = entry.boundingClientRect.y + entry.boundingClientRect.height >= dockRect.top;
      const entryHidden = entry.target.classList.contains('hide');
      if (doesOverlap) {
        if (!entryHidden) dock.style.zIndex = '0';
      } else {
        dock.style.zIndex = '999';
      }
    }
  };

  const io = new IntersectionObserver(callback, {
    threshold: [1],
    trackVisibility: true,
    delay: 100, // Set a minimum delay between notifications
  });
  for (const target of targets) io.observe(target);
});
