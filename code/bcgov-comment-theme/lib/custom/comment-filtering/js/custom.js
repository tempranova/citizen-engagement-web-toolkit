(function( $ ) {
 'use strict';
/**
 *
 *
 * @link       http://tempranova.com
 * @since      1.0.0
 *
 * @package    comment-filtering
 */
    
    // Initialize datepicker
    $('#comment_filtering_datepicker .input-daterange').datepicker({
        format: "yyyy-mm-dd",
        startDate: '-' + ($('#datepicker [name="time_ago"]').val()+1) + 'd', // when post was created in days (+1 for UX)
        endDate : '+1d' // tomorrow (+1 for UX)
    });
    
    // Get vars for AJAX
    var plugin_directory = $('#comment_filtering_plugin_dir').val();
    var post_id = $('main article:first-child').attr('class').split(' ')[0];
    post_id = post_id.substring(post_id.indexOf("-")+1);
    
    // Any time a tab is clicked, erase the values in various input fields (avoids extra AJAX calls)
    $('.cf_select_tab').click(function() {
        var search_term = $('[name=comment_filtering_search]').val('');
        var start_date = $('#datepicker [name="start"]').val('');
        var end_date = $('#datepicker [name="end"]').val('');
        var selected_range = $( "#cf_selected_range").val('default');;
    });
    
    // Event handler for click of button
    $('.comment_filtering_go').click(function() {
        // Set up all possible vars
        var search_term = $('[name=comment_filtering_search]').val();
        var start_date = $('#datepicker [name="start"]').val();
        var end_date = $('#datepicker [name="end"]').val();
        var selected_range = $( "#cf_selected_range").val();;
        
        // Send off AJAX calls with parameters
        // If has search term, send the term
        if(search_term&&!start_date&&!end_date) {
            $.ajax({
              url: plugin_directory + '/ajax_comment_filter.php?post_id=' + post_id + '&search_term=' + search_term,
              type : 'POST',
              dataType: 'html',
            }).done(function(data) {
                addContent(data);
            }).fail(function(err) {
                console.log(err);
            });
        }
        
        // This has one level of priority less than the preset date ranges
        if(start_date&&end_date&&!search_term&&selected_range=='default') {
            $.ajax({
              url: plugin_directory + '/ajax_comment_filter.php?post_id=' + post_id + '&start_date=' + start_date + '&end_date=' + end_date,
              type : 'POST',
              dataType: 'html',
            }).done(function(data) {
                addContent(data);
            }).fail(function(err) {
                console.log(err);
            });
        }
        
        if(selected_range&&selected_range!=='default'&&!search_term) {
            $.ajax({
              url: plugin_directory + '/ajax_comment_filter.php?post_id=' + post_id + '&selected_range=' + selected_range,
              type : 'POST',
              dataType: 'html',
            }).done(function(data) {
                addContent(data);
            }).fail(function(err) {
                console.log(err);
            });
        }
    });
    
    function addContent(data) {
        var articles = $(data).find('article');
        var htmlToAdd = '<p><strong>Your search has returned ' + articles.length + ' results.</strong></p>';
        $('.comment-list').html(htmlToAdd + data);
        // Hide pager (has unexpected behaviour, loading other non-searched comments)
        $('.pager').hide();
    }
    
})( jQuery );