/*global $ */
'use strict';

$(() => {
  // Move search form from top of main menu to header
  const $search_menu = $('#search-menu');
  if ($search_menu.length) {
    // There is a search-menu form in page, move it after #top-info-blog form element if exists
    const $top_info_blog = $('#top-info-blog');
    if ($top_info_blog.length) {
      $search_menu.insertAfter($('#top-info-blog'));
      $search_menu.addClass('tidy_moved');
      const $header = $('#header');
      if ($header.length) {
        $header.addClass('tidy_moved');
      }
    }
  }
});
