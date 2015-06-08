
var fprototype = '';

Admin.setup_collection_buttons = function(subject) {

    jQuery(subject).on('click', '.sonata-collection-add', function(event) {
        Admin.stopEvent(event);

        var container = jQuery(this).closest('[data-prototype]');
        var proto = container.attr('data-prototype');
        var protoName = container.attr('data-prototype-name') || '__name__';

        // Set field id
        var idRegexp = new RegExp(container.attr('id')+'_'+protoName,'g');
        proto = proto.replace(idRegexp, container.attr('id')+'_'+(container.children().length - 1));

        // Set field name
        var parts = container.attr('id').split('_');
        var nameRegexp = new RegExp(parts[parts.length-1]+'\\]\\['+protoName,'g');
        proto = proto.replace(nameRegexp, parts[parts.length-1]+']['+(container.children().length - 1));

        // remove the field label
        var labelRegex = new RegExp('<label.+>\\s+__name__label__\\s+<\/label>', 'gm');
        proto = proto.replace(labelRegex, '') + '<div class="clearfix"></div>';

        jQuery(proto)
            .insertBefore(jQuery(this).parent())
            .trigger('sonata-admin-append-form-element')
        ;

        jQuery(this).trigger('sonata-collection-item-added');
    });

    jQuery(subject).on('click', '.sonata-collection-delete', function(event) {
        Admin.stopEvent(event);

        jQuery(this).trigger('sonata-collection-item-deleted');

        jQuery(this).closest('.sonata-collection-row').remove();
    });
};
