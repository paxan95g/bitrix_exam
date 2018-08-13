/*
 * Подключить jQuery в init.php
 * CJSCore::Init(array("jquery2"));
*/
$(function(){
    $('.section-open').on('click', function(e) {
        e.preventDefault();

        var
            $this = $(this),
            items = $this.next('.section-items');

            items.stop(true).slideToggle();
    });
});