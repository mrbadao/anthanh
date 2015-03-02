/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here. For example:
    config.language = 'en';
    // config.uiColor = '#AADC6E';

    // xoa cac nut ko can thiet
    config.toolbar =
        [
            //['Source','-','Save','NewPage','Preview','-','Templates'],
            //['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
            //['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
            //['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
//'/',
        ['Bold'],['Italic'],['Underline'],['Strike'],['-'],
        //'Subscript','Superscript'

        ['TextColor'],['Link'],['Image'],['CustomImage'],['UploadImage'],['UploadVideo'],
        //['Styles','Format','Font','FontSize'],
        //['Maximize', 'ShowBlocks','-','About'],

        ['NumberedList'],['BulletedList'],['Blockquote'],
        ['JustifyLeft'],['JustifyCenter'],['JustifyRight']


        //['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
        //    '/',
    ];
    config.extraPlugins = 'CustomImage';  // add new plugin
    //config.extraPlugins = 'UploadImage';  // add new plugin
    // hien thi chuc nang uplaod hinh anh trong ckeditor
    config.filebrowserBrowseUrl = window.location.origin+'/ckplugin/ckfinder/ckfinder.html';

    config.filebrowserImageBrowseUrl = window.location.origin+'/ckplugin/ckfinder/ckfinder.html?type=Images';

    config.filebrowserFlashBrowseUrl = window.location.origin+'/ckplugin/ckfinder/ckfinder.html?type=Flash';

    config.filebrowserUploadUrl = window.location.origin+'/ckplugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

    config.filebrowserImageUploadUrl = window.location.origin+'/ckplugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

    config.filebrowserFlashUploadUrl = window.location.origin+'/ckplugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
    config.extraAllowedContent = 'iframe[*]'
};

// hide image button when loaded, cause we only need to use its library for the new CustomImage function.
CKEDITOR.on('instanceReady',function(){
    $(".cke_button__image").parent().parent().hide();
});

// custom image
CKEDITOR.plugins.add('CustomImage',
    {
        init: function (editor) {



            editor.ui.addButton('CustomImage',
                {
                    label: 'Custom Image',
                    command: 'CustomImage',
                    icon: CKEDITOR.plugins.getPath('CustomImage') + 'g_images.jpg'
                });
            editor.addCommand('CustomImage', { exec: CustomImage });


            editor.ui.addButton('UploadImage',
                {
                    label: 'Upload Image',
                    command: 'UploadImage',
                    icon: CKEDITOR.plugins.getPath('UploadImage') + 'upload_image.png'
                });
            editor.addCommand('UploadImage', { exec: UploadImage });

            editor.ui.addButton('UploadVideo',
                {
                    label: 'Upload Video',
                    command: 'UploadVideo',
                    icon: CKEDITOR.plugins.getPath('UploadVideo') + 'upload_video.png'
                });
            editor.addCommand('UploadVideo', { exec: UploadVideo });

        }
    });

/* +++++++++++++++++++++++++CustomImage's functions++++++++++++++++++++++++++*/

var imported = document.createElement('script');
imported.src = window.location.origin+'/ckplugin/ckeditor/plugins/CustomImage/main.js';
document.head.appendChild(imported);

/* +++++++++++++++++++++++++UploadImage's functions++++++++++++++++++++++++++*/


var imported = document.createElement('script');
imported.src = window.location.origin+'/ckplugin/ckeditor/plugins/UploadImage/main.js';
document.head.appendChild(imported);


/* +++++++++++++++++++++++++UploadVideo's functions++++++++++++++++++++++++++*/


var imported = document.createElement('script');
imported.src = window.location.origin+'/ckplugin/ckeditor/plugins/UploadVideo/main.js';
document.head.appendChild(imported);
