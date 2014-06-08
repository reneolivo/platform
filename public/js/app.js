(function($) {
    $(function() {
        $('#side-menu').metisMenu();
        $(window).bind('load', function() {
            $("html").addClass('window-loaded');
            //$(window).trigger('resize');
        });
        //Loads the correct sidebar on window load,
        //collapses the sidebar on window resize.
        $(window).bind("load resize", function() {
            width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 768) {
                $('div.sidebar-collapse').addClass('collapse');
            } else {
                $('div.sidebar-collapse').removeClass('collapse');
            }
        });
        $('.btn-destroy').on('click', function(e) {
            return window.confirm('Do you want to continue with this operation?');
        });
    });
})(window.jQuery);
