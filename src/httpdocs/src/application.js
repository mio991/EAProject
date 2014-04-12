/**
 * application.js
 * @copyright Jonas Arndt, Emre Konar, Daniel Treptow
 * @author Jonas Arndt, Emre Konar, Daniel Treptow
 */

// capture ajax callbacks
$.ajaxSetup({
    statusCode: {
        401: function(){ // Redirect to the login page.
            window.location.replace('#login');
        },
        403: function() { // 403 -- Access denied
            window.location.replace('#denied');
        }
    }
});

// main application
window.App = {
    Models: {},
    Collections: {},
    Routers: {},
    View: {},
    init: function() {
        window.current_session = new AuthorizedSession();

        App.router = new App.Routers.Main();
        Backbone.history.start();
    }
};

// application router
App.Routers.Main = Backbone.Router.extend({
    routes: {
        "": "home",
        "settings": "settings",
        "events": "events",
        "event/:id": "event",
        "imprint": "imprint",
        "contact": "contact",
        "login" : "login",
        "denied" : "denied"
    },
    initialize: function() {
        // register the regions
        Backbone.ViewManager.Core.addRegion('header', 'header');
        Backbone.ViewManager.Core.addRegion('footer', 'footer');
        Backbone.ViewManager.Core.addRegion('content', '#content');
        Backbone.ViewManager.Core.addRegion('current_user_panel', '#current_user_panel');

        Backbone.ViewManager.Core.swap('current_user_panel', new CurrentUserPanel.View().render());
        //Backbone.ViewManager.Core.addRegion('sidebar', '#sidebar');
    },
    home: function() {
        Backbone.ViewManager.Core.swap('content', new Home.View().render());
    },
    settings: function() {
        Backbone.ViewManager.Core.swap('content', new Settings.View());
    },
    events: function() {
        Backbone.ViewManager.Core.swap('content', new Events.View());
    },
    event: function($id) {
        Backbone.ViewManager.Core.swap('content', new Event.View({event_id: $id}));
    },
    imprint: function() {
        Backbone.ViewManager.Core.swap('content', new Imprint.View().render());
    },
    contact: function() {
        Backbone.ViewManager.Core.swap('content', new Contact.View().render());
    },
    login: function() {
        Backbone.ViewManager.Core.swap('content', new Login.View().render());
    },
    logout: function() {
        $('body #content').html('logout');
    },
    denied: function() {
        $('body #content').html('access denied!');
    }
});

// init app and start
$(document).ready(function() {
    App.init();
});