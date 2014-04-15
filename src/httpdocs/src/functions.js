var api_url = 'api/';
//var api_url = 'http://urlaub.tanyll.de/api/';

Backbone.View.prototype.template = function(tmpl, params) {
    // use handlebars
    //return tmpl(param);

    // use underscore
    return _.template(tmpl, params);
};

Backbone.View.prototype.loadTemplate = function(tpl_path, cb_success) {
    var that = this;

    if (void 0 == cb_success) {
        cb_success = function(template){
            that.$el.append(that.template(template));
        };
    }

    $.get('assets/views/'+tpl_path+'.tpl.html', cb_success);
};

// ease event debugging (http://jules.boussekeyt.org/2013/backbone-functions.html)
Backbone.Collection.prototype.debugEvents =
Backbone.Model.prototype.debugEvents =
Backbone.View.prototype.debugEvents =
Backbone.Router.prototype.debugEvents = function() {
    this.on('all', function(eventName) {
        console.log('[debug event] --> ', eventName, Array.prototype.slice.call(arguments, 1));
    });
};

// removes an element from collction and smootly fade out after removing
Backbone.View.prototype.kill = function() {
    var that = this;
    this.$el.fadeOut(500, function(){
        that.model.destroy();
        that.remove();
    });
};

// assign function for rendering subview trick (http://www.joezimjs.com/javascript/backbone-js-subview-rendering-trick/)
Backbone.View.prototype.assign =
Backbone.Router.prototype.assign = function (selector, view) {
    var selectors;

    if (_.isObject(selector)) {
        selectors = selector;
    } else {
        selectors = {};
        selectors[selector] = view;
    }

    if (!selectors) return;

    _.each(selectors, function (view, selector) {
        view.setElement(this.$(selector)).render();
    }, this);
};