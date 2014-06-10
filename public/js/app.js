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
            if (!window.confirm('Do you want to continue with this operation?')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        Dropzone.autoDiscover = false;

        $('.widget-dropzone').each(function() {
            var $self = $(this);
            this._dropzone = new Dropzone(this, {
                url: $self.attr('data-dropzone-url'),
                dictDefaultMessage: $self.attr('data-dropzone-title')
            });
        });

        $('.widget-datatable').dataTable();
        $('.widget-wysihtml5').wysihtml5();
        $('.widget-select2, .widget-combobox').select2();
        $('.widget-colorpicker').colorpicker();
        $('.widget-datepicker').datepicker({
            "format": "yyyy-mm-dd"
        });
    });
})(window.jQuery);
