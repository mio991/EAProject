/**
 * List.bb.js
 * @copyright Webnexx
 * @author Daniel Treptow
 */
var List = {};

List.Model = Backbone.Model.extend({
    defaults: {
        receipt: 0, // einnahmen
        cost: 0, // ausgaben
        stock: 0, // bestand
        contra_account: null, // gegenkonto
        proof_nr: null, // belegnummer
        date: null, // datum
        vat_rate: 19, // mehrwertsteuersatz
        description: null, // beschreibung

        position: 0,
        sequential_number: null,
        personal_id: 0
    },
    initialize: function() {
        if (this.get('date') === null) {
            this.set('date', moment().format("YYYY-MM-DD"));
        }

        if (this.get('position') != 'Pos.') {
            this.set('stock', Number(Book.list.getCurrentSaldo()).formatPrice());
        }
    }
});

List.Collection = Backbone.Collection.extend({
    model: List.Model,
    url: 'collection/bb_transaction',
    last_position: 0,
    initialize: function() {
        var that = this;

        that.on('add remove', function(item){
            that.refreshPositions();
            that.refreshSaldo();
        });

        // that.on('add', function(item){
        //     console.log(item);
        //     item.set('stock', this.getCurrentSaldo(item));
        // });
    },

    parse: function(response) {
        return response.transactions;
    },

    getStartSaldo: function() {

    },

    getCurrentSaldo: function(item) {
        var saldo = 0;

        //console.log();
        saldo += Number(Book.model.get('initial_balance'));

        this.each(function(k,v){
            saldo += Number(k.get('receipt'));
            saldo -= Number(k.get('cost'));
        });

        return saldo;
    },

    refreshPositions: function() {
        this.each(function(k,v){
            k.set('position', v+1);
        });
    },

    refreshSaldo: function() {
        var saldo = 0;
            saldo += Number(Book.model.get('initial_balance'));

        this.each(function(k,v){
            saldo += Number(k.get('receipt'));
            saldo -= Number(k.get('cost'));

            k.set('stock', saldo);
        });
    }
});
