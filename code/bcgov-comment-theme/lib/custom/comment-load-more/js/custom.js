/*
* In this file, we set up handlers for clicks on "See X Replies" buttons and do appropriate AJAX calls
* While there's a fair amount of DOM hopping in here, it should implement without issue for this case
*/

// Need to get dir of plugin for AJAX call, and post id for right comments
var plugin_directory = jQuery('#comment-ajax-plugin-directory').val();
var post_id = jQuery('main article:first-child').attr('class').split(' ')[0];
post_id = post_id.substring(post_id.indexOf("-")+1);

// This automatically loads ALL the subcomments for a given comment
jQuery('.load-more-comments').each(function() {
    jQuery(document).on("click", ".load-more-comments", function(e) {
        e.preventDefault();
        // Setting up various vars from the DOM
        var paged = jQuery('#comments-paged').val();
        var parent_li_id = jQuery(this).closest('article').parent().attr('id');
        var parent_comment_id = parent_li_id.substring(parent_li_id.indexOf("-")+1);
        // Get number of parent comments currently up
        var number_of_current_comments = 0;
        jQuery(this).parent().parent().prev('.reply-top-level').children().each(function() {
            number_of_current_comments = number_of_current_comments+1;
        });
        
        // Send off to a custom PHP file that will handle the ID and return straight HTML to append
        var url = plugin_directory + "/get_more_comments.php?post_id="+post_id+"&comment_index="+number_of_current_comments+"&parent_id="+parent_comment_id;
        var that = this;
        jQuery.ajax({
          url: url,
          type : 'POST',
          dataType: 'html',
        }).done(function(data) {
            // Testing number of items returned against total, for interface display
            var articles = jQuery(data).find('article');
            if(number_of_current_comments>0) {
                jQuery(that).parent().parent().prev('.reply-top-level').append(data);
            } else {
                jQuery(that).parent().after('<ul id="parent-comment-id-'+parent_comment_id+'" class="pager secondary-pager"><li class="next"><a href="#" class="load-more-comments paged-5" style="float:left;">More Replies →</a></li></ul>');
                jQuery(that).parent().hide().html(data).show();
            }
            // If fewer articles come along than the paged number, then fadeOut the button (both, in case another tree is triggered and it needs to go down the DOM)
            if(articles.length<parseInt(paged)) {
                jQuery(that).closest('.secondary-pager').fadeOut();
                jQuery('#parent-comment-id-'+parent_comment_id).hide();
            }
        }).fail(function(err) {
            console.log(err);
        });
    });
});
// This grabs another page of top-level comments according to amount user has set on options page
jQuery('.load-more-comments-paginated').click(function(e) {
    e.preventDefault();
    var paged = jQuery('#comments-paged').val();
    // Get number of parent comments currently up; this only grabs from the top-level "comment-list" div
    var number_of_current_comments = 0;
    jQuery('.comment-list').children('.depth-1').each(function() {
        number_of_current_comments = number_of_current_comments+1;
    });
    // Send off to a custom PHP file that will handle the ID and return stuff nicely
    var url = plugin_directory + "/get_more_comments.php?post_id="+post_id+"&comment_index="+number_of_current_comments;
    var that = this;
    jQuery.ajax({
      url: url,
      type : 'POST',
      dataType: 'html',
    }).done(function(data) {
        // Remove "Newer Comments" button if it returns less than the paginated amount
        var articles = jQuery(data).find('article');
        if(articles.length<parseInt(paged)) {
            jQuery('.load-more-comments-paginated').fadeOut();
        }
        jQuery('.comment-list').append(data);
    }).fail(function(err) {
        console.log(err);
    });
});
