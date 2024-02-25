define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.StockSetup', null, {
        constructor() {
            this.colour_names = ['', 'Succulent', 'Flowering', 'Foliage', 'Vining', 'Unusual'];
            this.cardwidth = 100;
            this.cardheight = 152;

            this.category = null;
            this.stock_class = null;
            this.toolkit = null;
            this.server = null;
            this.gamethemeurl = null;
        },
        SetURLPrefix(gamethemeurl){this.gamethemeurl = gamethemeurl},
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetStockClass(stock_class){this.stock_class = stock_class},
        setCategory(category){this.category = category},
        SetServer(server){this.server = server},

        setup(div){
            hand = new this.stock_class();
            hand.create(this.server, div, this.cardwidth, this.cardheight);
            hand.image_items_per_row = 12;
            for (var colour = 0; colour <= 5; colour++) {
                for (var type = 0; type <= 11; type++) {
                    var card_type_id = this.getCardTypeID(colour, type);
                    hand.addItemType(card_type_id, card_type_id, this.gamethemeurl+'img/' + this.category + '.png', card_type_id);
                }
            }
            hand.onItemCreate = this.toolkit.hitch( this, 'setupNewCard' );
            return hand;
        },
        setupNewCard: function(card_div, card_type_id, card_id)
        {
            console.log('setupNewCard');
            console.log(card_div);
            console.log(card_type_id);
            console.log(card_id);
           // Add a special tooltip on the card:
           this.server.addTooltip(card_div.id, "" + this.colour_names[Math.floor(card_type_id/12)]);
        },
        getCardTypeID: function(colour, index) {
            return 12*colour + index;
        },
    });
});
