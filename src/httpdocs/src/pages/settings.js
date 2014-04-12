var Settings = {};
Settings.Model = Backbone.Model.extend({
    url: 'model/bb_user?id=1',
    defaults: {
        company_name: null,
        first_name: null,
        last_name: null,
        telephone: null,
        email: null,
        username: null
    }
});
Settings.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'section',
    className: 'settings',
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');
        //this.model.url = 'model/bb_user';
        this.model = new Settings.Model();
        // this.model.fetch({
        //     data: {
        //         id : 1
        //     }});
        this.model.fetch({success: function(){that.render()}});

        return this;
    },
    render:function () {
        var that = this;

        this.loadTemplate('pages/settings', function(template){
            //that.$el.append(that.template(template));
            that.$el.append(that.template(template, that.model.toJSON()));
        });

        return this;
    }
});