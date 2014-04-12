AuthorizedSession = Backbone.Model.extend({
    url: 'data/login.php',
    defaults: {
        sessionId: null,
        email: '',
        password: '',
        user_id: null,
        customer_id: null,
        role: null,
        first_name: '',
        last_name: '',
        company_name: '',
        no: null
    },

    isAuthorized: function(){
        var that = this;

        if (this.get("sessionId") !== null && this.get("sessionId") != '') {
            return Boolean(this.get("sessionId"));
        }

        var localStorageSession = localStorage.getItem("sessionId");
        if (localStorageSession !== null) {
            this.set('sessionId', localStorageSession);
            this.save({'auth':true},{
                success: function(data){
                    $('#top_navigation').fadeIn();
                    that.trigger('loggedin');
                }
            });
        }

        return false;
    },

    login: function(){

        window.location.replace('#events');
        $('#top_navigation').fadeIn();
        Backbone.ViewManager.Core.swap('current_user_panel', new CurrentUserPanel.View());
        this.trigger('loggedin');
    },

    logout: function() {
        $.ajax({
            url: 'data/logout.php',
            type: 'POST',
            dataType: "json",
            success: function (data) {}
        });

        localStorage.clear();

        window.current_session.id = '';
        window.current_session.clear();

        window.location.replace('#login');

        Backbone.ViewManager.Core.swap('current_user_panel', new CurrentUserPanel.View());

        //window.location.reload();

        this.trigger('loggedout');
        $('#top_navigation').fadeOut();
    }
});