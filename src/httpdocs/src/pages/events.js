var Events = {};
Events.list = new EventsList.Collection();
Events.Model = Backbone.Model.extend({
    //urlBase: api_url+'model/bb_events',
    url: api_url,
    urlRoot: api_url+'model/bb_events',
    defaults: {
        position: null,
        user_id: null,
        title: null,
        comment: 0,
        begin: null,
        end: null
    }
});
Events.ViewAddEntry = Backbone.View.extend({
    model: null,

    initialize: function() {
        var that = this;
        _.bindAll(this, 'render');

        if (void 0 == this.options.event_id) {
            console.log('no event_id given');
            return false;
        }

        this.event_id = this.options.event_id;
        this.model = new EventsList.Model();

        return this;
    },

    events: {
        'change input': function(event){
            var that = this,
                $target = $(event.target),
                param_name  = $target.attr('name'),
                param_value = $target.val();

            var param = {};
            param[param_name] = param_value;
            that.model.set(param);
        },
        // 'change [name="cost"], [name="receipt"]': function(model){
        //     var stock = event.list.getCurrentSaldo();
        //         stock -= Number(this.$el.find('[name="cost"]').val());
        //         stock += Number(this.$el.find('[name="receipt"]').val());

        //     this.$el.find('[name="stock"]').val(Number(stock).formatPrice());
        // },
        // 'change select': function(event){
        //     var that = this,
        //         $target = $(event.target),
        //         param_name  = $target.attr('name'),
        //         param_value = $target.val();

        //     var param = {};
        //     param[param_name] = param_value;
        //     that.model.set(param);
        // },
        'click button.create': function(event){
            var that = this;
            event.preventDefault();

            this.model.set({event_id: this.event_id});

            Events.list.create(this.model, {'wait': true,
                success: function(data){
                    that.model = new EventsList.Model();
                    that.render();
                    that.trigger('created',data);
                }
            });

            this.trigger('save');
        }
    },

    render: function(){
        var that = this;
        this.$el.empty();
        //this.loadTemplate('modules/transaction_add_item');

        this.loadTemplate('modules/events_add_item', function(template){
            that.$el.append(that.template(template, that.model.toJSON()));
        });

        return this.el;
    }
});

Events.ViewListItem = Backbone.View.extend({
    model: null,
    tagName: 'article',
    className: 'list_item',

    initialize: function() {
        var that = this;
        _.bindAll(this, 'render');

        this.model.on('change:position', function(param){
            that.render();
        });

        return this.render();
    },

    events: {
        'click .remove': function(event) {
            event.preventDefault();

            if (!confirm("Wollen Sie Urlaub Nr.: '"+this.model.get('position')+"' wirklich löschen?")) {
                return false;
            }

            this.kill();
        },
        'click .edit': function(event) {
            event.preventDefault();

            // if (!confirm("Wollen Sie Urlaub Nr.: '"+this.model.get('position')+"' wirklich bearbeiten?")) {
            //     return false;
            // }

            this.edit();
        }
    },

    render: function(){
        var that = this;
        //that.model.set('position','-');
        this.loadTemplate('modules/event_item', function(template){
            that.$el.html(that.template(template, that.model.toJSON()));
        });

        return this.el;
    },

    edit: function(){
        // var view_new_entry = new Events.ViewAddEntry({'event_id': this.model.get('id')}).render();
        var view_new_entry = new Events.ViewAddEntry({model: this.model});
        var that = this;

        view_new_entry.on('save',function(){that.render();});

        this.$el.html(view_new_entry.render());
    }
});

Events.View = Backbone.ViewManager.BaseView.extend({
    event_id: 0,
    collection: Events.list,
    view_new_entry: null,
    tagName: 'section',
    className: 'events',
    year: null,
    events: {
        'click .print': "showPdf",
        'change select[name="filter_month"]': "search",
        'change select[name="filter_year"]': "search"
    },
    initialize: function() {
        var that = this;
        _.bindAll(this, 'render');


            that.view_new_entry = new Events.ViewAddEntry({'event_id': that.event_id});

            // that.view_new_entry.on('created', function(data){
            //     var date = data.get('date').split('-');
            //     var year = date[0];
            //     var month = date[1];
            //     var changed = false;

            //     if (that.$el.find('select[name="filter_month"]').val() != month) {
            //         that.$el.find('select[name="filter_month"]').val(month);
            //         changed = true;
            //     }

            //     if (that.$el.find('select[name="filter_year"]').val() != year) {
            //         that.$el.find('select[name="filter_year"]').val(year);
            //         changed = true;
            //     }

            //     if (changed === true) {
            //         that.search();
            //     }
            // });

            that.collection.on('add', function(item){
                var transaction_item = new Events.ViewListItem({model: item});
                that.$el.find('.events').append(transaction_item.$el);
            });

            that.render();

        return this;
    },
    render: function(){
        var that = this;

        this.loadTemplate('pages/events', function(template){
            that.$el.append(that.template(template));
            that.$el.find('select[name="filter_month"]').val(moment().format('MM')).attr('selected', 'selected');

            that.assign({
                '.events_add': that.view_new_entry
            });

            //append heading line
            var list_heading = new Events.ViewListItem({model: new EventsList.Model({
                position: '&nbsp;',
                title: 'Titel',
                comment: 'Kommentar',
                begin: 'Urlaub von',
                end: 'Urlaub bis'})});

            that.$el.find('.events_head').append(list_heading.$el);

            that.search();
        });

        return this;
    },
    search: function() {
        var that = this;
        var current_year = $('select[name="filter_year"]').val();

        this.collection.reset();
        this.$el.find('#list').empty();
        // this.collection.comparator = function(model) {
        //     return model.get('begin');
        // }



        this.collection.fetch({
            'add':true,
            data: {
                event_id : this.event_id
            },
            success: function(collection, response){
                console.debug(collection);
                // message on empty transactions
                if (collection.length === 0) {
                    that.$el.find('.events').append('Keine Urlaube vorhanden.');
                }
                // call the sort method
                // this.collection.sort();
            }});
    },
    unsetView: function() {
        this.collection.reset();
    },
    showPdf: function() {
        //console.debug('generating pdf and show, currently disabled');

        var doc = new jsPDF();
            doc.setProperties({
                // '+this.model.get('title')+'
                title: 'Urlaube  ',
                subject: 'Uralaubsübersicht',
                author: 'Emre Konar, Jonas Arndt, Daniel Treptow',
                keywords: '',
                creator: 'Urlaubsplaner von Emre Konar, Jonas Arndt und Daniel Treptow'
            });

            // heading
            doc.setFont("helvetica");
            doc.setFontType("bold");
            doc.setFontSize(22);

            doc.text(10, 20, 'Urlaub');

            // transactions list heading
            doc.setFont("courier");
            doc.setFontSize(8);
            doc.setFontType("bold");

            doc.text(10, 30, 'title');
            doc.text(40, 30, 'comment');
            doc.text(115, 30, 'begin');
            doc.text(150, 30, 'end');

            // transactions list
            doc.setFontType("normal");
            var pos_y = 35;
            this.collection.each(function(events) {
                doc.text(10, pos_y, events.get('title').toString());
                doc.text(40, pos_y, events.get('comment').toString());
                doc.text(115, pos_y, events.get('begin').toString());
                doc.text(150, pos_y, events.get('end').toString());
                pos_y += 3;
            });

            // finally output or save or something
            doc.output('datauri');
    }
});