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
        // var formValues = {
        //     email: $('#inputEmail').val(),
        //     password: $('#inputPassword').val()
        // };

        // $.ajax({
        //     url: 'data/login.php',
        //     type: 'POST',
        //     dataType: "json",
        //     data: formValues,
        //     success: function (data) {
        //         console.log(["Login request details: ", data]);

        //         // show errors
        //         if(data.error) {
        //             alert(data.error.text);
        //             //$('.alert-error').text(data.error.text).show();
        //         } else {
        //             window.location.replace('#');
        //         }
        //     }
        // });

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
                //console.log(["Login request details: ", data]);
                //console.log(window.current_session.toJSON());
                localStorage.setItem("sessionId", data.get('sessionId'));

                window.current_session.login();
            }
        });
    }
});