var Settings = {};
Settings.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'section',
    className: 'settings',
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');

        return this;
    },
    render:function () {
        var that = this;

        this.loadTemplate('pages/settings', function(template){
            that.$el.append(that.template(template));
        });

        return this;
    }
});