$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Sidebar toggle functionality
    $(document).on('click', '.btn-toggle-fullwidth', function(e) {
        e.preventDefault();
        
        var $sidebar = $('#left-sidebar');
        var $button = $(this);
        var $icon = $button.find('i');
        
        // Toggle minified state
        $sidebar.toggleClass('minified');
        
        // Update icon and tooltip
        if ($sidebar.hasClass('minified')) {
            $icon.removeClass('fa-bars').addClass('fa-arrow-right');
            // $button.attr('data-original-title', 'Expand Sidebar');
        } else {
            $icon.removeClass('fa-arrow-right').addClass('fa-bars');
            // $button.attr('data-original-title', 'Collapse Sidebar');
        }
        
        // Update tooltip text
        $button.tooltip('dispose').tooltip();
        
        // Prevent default behavior
        $('body').removeClass('layout-fullwidth');
    });
});