(function( $ ) {
	'use strict';
/*
A user selects a sort by date toggle:
the initial state is most recent.
Toggle and the comments are returned by oldest.
Toggle again and the sort and the comments are returned by most recent.

A user selects to sort by number of replies toggle, the initial state is default, with no sort applied at all.
Toggle once and the comments are returned by the highest number of replies,
toggle twice and the comments are returned by the least number of replies,
and finally toggle three times and the comments are returned at the default.
*/
  function returnLocation(){
    var i,
      searchArray = [],
      split1,
      split2,
      trim1,
      winLoc;
    
    winLoc = window.location;

    if (winLoc.search !== '') {
      trim1 = winLoc.search.replace(/^\?/,'');
      split1 = trim1.split('&');
      for (i = 0; i < split1.length; i += 1) {
        split2 = split1[i].split('=');
        if (split2[0]){
          searchArray.push({
            name: split2[0],
            value: (split2[1] ? split2[1] : '')
          });
        }

      }
    }

    return {
      hash: winLoc.hash,
      origin: winLoc.origin, 
      pathname: winLoc.pathname,
      search: winLoc.search,
      searchArray: searchArray
    };
  }

  function getURL(){
    var loc;

    loc = returnLocation();
  }

/*
js-co-input-sort
js-co-input-dir
*/

  // cycles between asc & desc
  function eventClickDate(jQElement){
    var dir,
      state,
      jQInputSort,
      jQInputDir;

    jQInputSort = $('.js-co-input-sort');
    jQInputDir = $('.js-co-input-dir');

    //set sort
    jQInputSort.val('date');

    // handles date state
    state = jQElement.attr('data-state');
    switch(state){
      case 'is-asc':
        //asc to desc
        dir = 'desc';
      break;
      case 'is-desc':
        //asc to desc
        dir = 'asc';
      break;
      case '':
        //asc to desc
        dir = 'desc';
      break;
      default:
        //desc to asc
        dir = 'desc';
    }

    //set dir
    jQInputDir.val(dir);

    //submit form
    $('.js-co-form').submit();
  }

  /*
  '' > desc
  desc > asc
  asc > ''
  */
  function eventClickReplies(jQElement){
    var dir,
      state,
      jQInputSort,
      jQInputDir;

    jQInputSort = $('.js-co-input-sort');
    jQInputDir = $('.js-co-input-dir');

    // handles date state
    state = jQElement.attr('data-state');
    switch(state){
      case 'is-asc':
        //asc to desc
        dir = '';
      break;
      case 'is-desc':
        //asc to desc
        dir = 'asc';
      break;
      case '':
        //asc to desc
        dir = 'desc';
      break;
      default:
        //desc to asc
        dir = 'desc';
    }

    //set sort
    if (dir === '') {
      jQInputSort.val('date');
    } else {
      jQInputSort.val('replies');
    }

    //set dir
    jQInputDir.val(dir);

    //submit form
    $('.js-co-form').submit();
  }


   $(function() {
     $('body').on('click', '.js-co-btn-sort-date', function(e){
      eventClickDate($(this));
     });
     $('body').on('click', '.js-co-btn-sort-replies', function(e){
      eventClickReplies($(this));
     });
   });
})( jQuery );
