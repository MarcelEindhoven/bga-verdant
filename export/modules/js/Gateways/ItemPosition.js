define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.ItemPosition', null, {
        constructor(element_id) {
            this.element_id = element_id;

            this.itemwidth = 50;
            this.itemheight = 50;
        },
        setWebToolkit(toolkit){this.toolkit = toolkit},
        setWebGateway(web_gateway){this.web_gateway = web_gateway},
        set: function(item) {
            type = this.itemwidth * Number(item['type']);
            color = this.itemheight * Number(item['type_arg']);
            block = this.web_gateway.format_block('jstpl_item', {nr:item.id, background_horizontal: color, background_vertical: type});
            this.toolkit.place(block, this.element_id);
        },
        discard: function() {
        },
        getTypeID: function(item) {
            return this.getCardTypeID(+item['type'], +item['type_arg']);
        },
        getCardTypeID: function(colour, index) {
            return 12*colour + index;
        },

    });
});
