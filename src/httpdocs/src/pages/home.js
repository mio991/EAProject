var Home = {};
Home.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'section',
    className: 'home',
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');

        return this;
    },
    render:function () {
        var that = this;

        this.loadTemplate('pages/home', function(template){
            that.$el.append(that.template(template));
        });

        return this;
    }
});