var Imprint = {};
Imprint.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'section',
    className: 'imprint',
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');

        return this;
    },
    render:function () {
        var that = this;

        this.loadTemplate('pages/imprint', function(template){
            that.$el.append(that.template(template));
        });

        return this;
    }
});