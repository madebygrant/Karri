//TinyMCE editor in the Theme Customizer.
( function( $ ) {
    wp.customizerCtrlEditor = {
        init: function() {
            $(window).load(function(){
                $('textarea.wp-editor-area').each(function(){
                    var tArea = jQuery(this),
                        id = tArea.attr('id'),
                        editor = tinyMCE.get(id),
                        setChange,
                        content;

                    if(editor){
                        editor.on('change', function () {
                            editor.save();
                            content = editor.getContent();
                            clearTimeout(setChange);
                            setChange = setTimeout(function(){
                                tArea.val(content).trigger('change');
                            },500);
                        });
                    }

                    tArea.css({
                        visibility: 'visible'
                    }).on('keyup', function(){
                        content = tArea.val();
                        clearTimeout(setChange);
                        setChange = setTimeout(function(){
                            content.trigger('change');
                        }, 500);
                    });
                });
            });
        }
    };
    wp.customizerCtrlEditor.init();
})(jQuery);
