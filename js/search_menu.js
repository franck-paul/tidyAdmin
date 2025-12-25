/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Move search form from top of main menu to header
  const search_menu = document.getElementById('search-menu');
  if (search_menu) {
    // There is a search-menu form in page, move it after #top-info-blog form element if exists
    const top_info_blog = document.getElementById('top-info-blog');
    if (top_info_blog) {
      top_info_blog.after(search_menu);
      search_menu.classList.add('tidy_moved');
      const header = document.getElementById('header');
      if (header) {
        header.classList.add('tidy_moved');
      }
    }
  }
});
