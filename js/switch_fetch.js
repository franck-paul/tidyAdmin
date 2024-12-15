/*global dotclear */
'use strict';

dotclear.ready(() => {
  const svgOn =
    '<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="m0 0h48v48h-48z" opacity="0"/><g fill="none"><path d="m0 0h24v24h-24z" transform="scale(2)"/><path d="m20 11c-.50272384-3.61746266-3.36413073-6.45106703-6.98632935-6.9184475-3.62219863-.46738047-7.10916219 1.54707933-8.51367065 4.9184475m-.5-4v4h4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="scale(2)"/><path d="m4 13c.50272384 3.61746266 3.36413073 6.45106703 6.98632935 6.9184475 3.62219863.46738047 7.10916219-1.54707933 8.51367065-4.9184475m.5 4v-4h-4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="matrix(2 0 0 2 0 0)"/></g></svg>';
  const svgOff =
    '<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path d="m0 0h48v48h-48z" opacity="0"/><g fill="none"><path d="m0 0h24v24h-24z" transform="scale(2)"/><path d="m20 11c-.34919763-2.51212885-1.85491385-4.71566486-4.06840156-5.9538904-2.21348772-1.23822554-4.87917082-1.36817127-7.20259844-.3511096m-2.41 1.624c-.78105512.76174768-1.39981578 1.67373081-1.819 2.681m-.5-4v4h4" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="scale(2)"/><path d="m4 13c.42030755 3.02445089 2.50575137 5.55551942 5.39396632 6.54656736 2.88821495.99104793 6.08832553.27363866 8.27703368-1.85556736m2.329-1.691v-1h-1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="matrix(2 0 0 2 0 0)"/><path d="m3 3 18 18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" transform="scale(2)"/></g></svg>';

  // Create the buttons
  const btnOn = dotclear.htmlToNode(`<button id="tidy-switch-fetch" class="tidy-fetch-on">${svgOn}</button>`);
  const btnOff = dotclear.htmlToNode(`<button id="tidy-switch-fetch" class="tidy-fetch-off">${svgOff}</button>`);

  // Set current mode
  const switchMode = (stop = false) => {
    if (stop) {
      dotclear.servicesOff = true;
      btnOff.style.display = '';
      btnOn.style.display = 'none';
    } else {
      dotclear.servicesOff = false;
      btnOff.style.display = 'none';
      btnOn.style.display = '';
    }
  };

  // Cope with click on buttons
  btnOn.addEventListener('click', (event) => {
    if (!dotclear.servicesOff) switchMode(true);
    event.preventDefault();
  });
  btnOff.addEventListener('click', (event) => {
    if (dotclear.servicesOff) switchMode(false);
    event.preventDefault();
  });

  // Add buttons in header
  switchMode(dotclear.servicesOff);
  document.querySelector('h1')?.after(btnOn);
  document.querySelector('h1')?.after(btnOff);
});
