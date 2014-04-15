var CurrentUserPanel = {};
CurrentUserPanel.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'div',
    model: null,
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');
        this.model = window.current_session;

        this.model.on('loggedin', function(){
            that.render();
        });

        this.model.on('loggedout', function(){
            that.render();
        });

        return this;
    },

    events: {
        "click .btn_logout": function(){
            window.current_session.logout();
        },
        "click": function(){
            if (this.$el.find('.popout>div').css('display') == 'none') {
                this.$el.find('.popout>div').slideDown();
                return true;
            }

            this.$el.find('.popout>div').slideUp();
        }
    },

    render: function () {
        var that = this;

        // if (that.model.isAuthorized() === false) {
        //     //that.$el.html();
        //     var button = $('<a href="#login">Anmelden</a><img src="assets/img/icons_user.png">').click(function(){
        //         window.location.replace('#login');
        //     });
        //     that.$el.html(button);
        //     return that;
        // }
        console.log('render');

        if (that.model.isAuthorized() !== false) {
            that.loadTemplate('modules/current_user_panel', function(template){
                that.$el.html(that.template(template, that.model.toJSON()));
            });
        } else {
            that.loadTemplate('modules/current_user_panel_unauthorized', function(template){
                that.$el.html(that.template(template, that.model.toJSON()));
            });
        }

        // that.loadTemplate('modules/current_user_panel', function(template){
        //     that.$el.html(that.template(template, that.model.toJSON()));
        // });

        return that;
    }
});