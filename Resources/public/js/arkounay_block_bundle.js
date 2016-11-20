$(function(){

    // Tiny MCE init:
    tinymce.init({
        selector: ".js-arkounay-block-bundle-block, .js-arkounay-block-bundle-entity",
        inline: true,
        menubar:false,
        statusbar: false,
        plugins: [
            "advlist autolink link lists charmap hr anchor pagebreak template",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
            "table contextmenu directionality paste textcolor code save"
        ],
        image_dimensions: false,
        toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist | link image | forecolor backcolor | code save",
        extended_valid_elements: "div[*],meta[*],span[*]",
        valid_children: "+body[meta],+div[h2|span|meta|object],+object[param|embed]",
        valid_elements: '*[*]',
        forced_root_block: '',
        remove_script_host: false,
        cleanup: false,
        entity_encoding: "raw",
        convert_urls: false,
        save_onsavecallback: function () {
            entitiesCallbackSaveAjax();
        }
    });


    // Tiny MCE init (plain):
    tinymce.init({
        selector: ".js-arkounay-block-bundle-entity-plain",
        inline: true,
        menubar:false,
        statusbar: false,
        plugins: [
            "paste save"
        ],
        toolbar: "insertfile undo redo save code",
        invalid_elements: 'b, strong, i, em, span',
        forced_root_block: '',
        cleanup: true,
        entity_encoding: "raw",
        paste_as_text: true,
        save_onsavecallback: function (ed) {
            entitiesCallbackSaveAjax();
        }
    });

    /**
     * Save all the editable blocks in the page
     */
    function entitiesCallbackSaveAjax() {
        var $tinymce = $('.js-arkounay-block-bundle-block, .js-arkounay-block-bundle-entity');
        var blocks = [];
        $tinymce.each(function(i, block){
            var $block = $(block);
            blocks.push({
                id: $block.data('id'),
                content: tinymce.get($block.attr('id')).getContent(),
                entity: $block.data('entity'),
                field: $block.data('field')
            });
        });

        $.ajax({
            url: ajax_edit_pageblocks_url,
            type: "POST",
            data: {blocks: blocks},
            success: function (res) {
                // succès
            },
            error: function () {
                alert('An error occured.');
            }
        });
    }

});