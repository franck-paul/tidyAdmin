/*global $ */
'use strict';

$(() => {
  // Clone media search form input to visible area
  const $search_media = $('#filters-form #q').parent();
  if ($search_media.length) {
    const $recent_media = $('p.media-recent');
    if ($recent_media.length) {
      const $cloned = $search_media.clone();
      const $cloned_input = $cloned.children('#q');
      const $cloned_label = $cloned.children('label');
      $cloned_input.attr('id', 'qq');
      $cloned_input.attr('name', 'qq');
      $cloned_label.attr('for', 'qq');
      $cloned.insertAfter($recent_media);
      $cloned.addClass('tidy_moved');
      $recent_media.addClass('tidy_moved');
      // Simulate form submit on enter key in input cloned field
      const $filtersform = $('#filters-form');
      if ($filtersform.length) {
        $cloned_input.on('keyup', (e) => {
          if (e.key == 'Enter') {
            $search_media.children('#q').val($cloned_input.val());
            $filtersform[0].submit();
          }
        });
      }
    }
  }
});
