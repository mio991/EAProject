var Contact = {};
Contact.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'section',
    className: 'contact',
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');

        return this;
    },
    render:function () {
        var that = this;

        this.loadTemplate('pages/contact', function(template){
            that.$el.append(that.template(template));
        });

        return this;
    }
});