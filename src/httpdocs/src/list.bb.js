/**
 * List.bb.js
 * @copyright Jonas Arndt, Emre Konar, Daniel Treptow
 * @author Jonas Arndt, Emre Konar, Daniel Treptow
 */
var List = {};

List.Model = Backbone.Model.extend({
    defaults: {
        book_id: 0,
        receipt: 0, // einnahmen
        cost: 0, // ausgaben
        stock: 0, // bestand
        contra_account: null, // gegenkonto
        proof_nr: null, // belegnummer
        date: null, // datum
        vat_rate: 7 // mehrwertsteuersatz
    }
});

List.Collection = Backbone.Collection.extend({
    model: List.Model,
    url: 'list'
});