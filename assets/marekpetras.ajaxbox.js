(function ($) {
    var Box = function (selector, options) {

        this.element = $(selector);

        this.source = this.element.data('source');
        this.body = this.element.find('.box-body');
        this.options = $.extend({},this.defaults,options);
        this.data = this.options.data;

        if (!this.inititated) {
            this.init(options);
        }

        return this;
    };

    Box.prototype = {
        defaults: {
            autoload: true,
            onerror: function(response, box, xhr) {
                box.body.html('<div class="alert alert-danger">'+response+'</div>');

                return box;
            },
            onload: function(box, status) {
                // onload signature
                return box;
            }
        },
        init: function (options) {
            this.inititated = true;

            if ( !this.source ) {
                this.element.find('.reload').remove();
            }

            // ajax
            if ( this.options.autoload && this.source ) {
                this.reload(this.data, this.options.onload);
            }
            // static
            else {
                if ( typeof this.data.onload === typeof Function ) {
                    this.data.onload(this,'success');
                }
            }
        },
        testme: function () {
            console.log(this);
        },
        hide: function () {
            return this.element.hide();
        },
        show: function () {
            return this.element.show();
        },
        toggle: function () {
            return this.element.toggle();
        },
        addOverlay: function () {
            return this.element.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        },
        removeOverlay: function () {
            return this.element.find('.overlay').remove();
        },
        source: function(source) {
            if (source) {
                this.source = source;
            }

            return this.source;
        },
        collapse: function() {
            return this.element.find('button[data-widget=collapse]').trigger('click');
        },
        reload: function (data, callback) {

            if ( data ) {
                this.data = data;
            }

            if (this.source) {
                var o = this;
                this.addOverlay();

                this.body.load(this.source, this.data, function( response, status, xhr ) {
                    if ( status == 'error' ) {
                        // do error if desired
                        if ( typeof o.options.onerror === typeof Function ) {
                            o.options.onerror(response, o, xhr);
                        }
                    }
                    else {

                        if ( typeof callback === typeof Function ) {
                            callback(o, status);
                        }
                    }

                    o.removeOverlay();
                });
            }

            return this;
        }
    };

    // add it to the jQuery API
    jQuery.addObject('box', Box);

})(jQuery);




