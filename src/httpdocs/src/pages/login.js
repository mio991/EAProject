var Login = {};
Login.View = Backbone.ViewManager.BaseView.extend({
    tagName: 'section',
    className: 'auth',
    initialize:function () {
        var that = this;
        _.bindAll(this, 'render');

        return this;
    },

    events: {
        "click #loginButton": "login"
    },

    render:function () {
        var that = this;
        this.loadTemplate('modules/login', function(template){
            that.$el.append(that.template(template));
        });

        return this;
    },

    login:function (event) {
        var that = this;
        event.preventDefault();

        window.current_session.set({
            email: $('#inputEmail').val(),
            password: $('#inputPassword').val()
        },{
            silent:true
        });
        window.current_session.save({'auth':false},{
            error: function(data){
                alert('Fehler beim Login');
            },
            success: function(data){
                localStorage.setItem("sessionId", data.get('sessionId'));
                window.current_session.login();
            }
        });
    }
});