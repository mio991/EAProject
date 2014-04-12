/**
 * List.bb.js
 *
 * @project EAProject
 * @copyright Jonas Arndt, Emre Konar, Daniel Treptow
 * @author Jonas Arndt, Emre Konar, Daniel Treptow
 */
var EventsList = {};

EventsList.Model = Backbone.Model.extend({
    defaults: {
        user_id: 0, // einnahmen
        title: 0, // ausgaben
        comment: 0, // bestand
        begin: null, // gegenkonto
        end: null
    },
    initialize: function() {
        if (this.get('begin') === null) {
            this.set('begin', moment().format("YYYY-MM-DD"));
        }

        if (this.get('end') === null) {
            this.set('end', moment().format("YYYY-MM-DD"));
        }
    }
});

EventsList.Collection = Backbone.Collection.extend({
    model: EventsList.Model,
    url: 'collection/bb_events',
    last_position: 0,
    initialize: function() {
        var that = this;

        that.on('add remove', function(item){
            that.refreshPositions();
        });

        that.on('add', function(item){
            console.log(item);
        });
    },

    parse: function(response) {
        return response;
    },

    refreshPositions: function() {
        this.each(function(k,v){
            k.set('position', v+1);
        });
    }
});
