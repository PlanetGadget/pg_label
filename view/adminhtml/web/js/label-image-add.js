
define([
    'jquery',
    'picturefill',
    'bannermediabrowser'
], function($) {

    return function() {
        var url = '';
        var body = $('body'),
            tblBody =$('#mytbodyis');

        body.on('click', '.mybuttonsis', function(){

            try {
                $(this).addClass('addImageTothis');
                // var url = '/admin/cms/wysiwyg_images/index/page_click_url/page_content/store/page_click_url/type/image/"';

                if(window.location.pathname.split('/')[1] == 'admin'){
                    url = '/admin/cms/wysiwyg_images/index/';
                    console.log(url);
                } else {

                    url = '/'+window.location.pathname.split('/')[1]+'/cms/wysiwyg_images/index/';
                    console.log(url);
                }

                window.bannerMediabrowserUtility.openDialog(url);
            } catch (e){
                console.log(e);
            }

        }).on('map_image_collection_JSON', function(){

            var srcset = [];

            tblBody.find('tr').map(function(k,y){

                var obj = {
                    'path': jQuery('.image-prod-img [type=hidden]',y).val(),
                    'media' : jQuery('.image-media-query [type=text]',y).val()
                };

                srcset.unshift(obj);

            });

            $('#page_image_collection_JSON').val(JSON.stringify(srcset));

            $('.image-btns a').show();

            // edit btn and image relate stuff
            var noSrcImg = tblBody.find('tr td [src=""], tr td [src="'+window.location.origin+'"]');

            noSrcImg.parent().next('.image-btns').find('a').hide();

            tblBody.find( 'tr td span' ).hide();

            noSrcImg.parent().next('.image-btns').find('.addimg').show();
            //tblBody.find( 'tr td [src]:not([src=""], [src="'+window.location.origin+'"])').parent().next('.image-btns').find('.chngimg').show();
            tblBody.find()

        }).on('click', '.image-delete-link', function(e){
            // remove image version
            e.preventDefault();
            $(this).closest('tr').find('.image-prod-img img').attr('src', '');
            $(this).closest('tr').find('.imagerelpath').val('');
            $('body').trigger('map_image_collection_JSON');

        });


        body.trigger('map_image_collection_JSON');


    };
});





