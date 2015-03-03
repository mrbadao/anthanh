/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
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

        ['TextColor'],['Link'],['Image'],['Table'],['CustomImage'],
        //['Styles','Format','Font','FontSize'],
        //['Maximize', 'ShowBlocks','-','About'],

        ['NumberedList'],['BulletedList'],['Blockquote'],
        ['JustifyLeft'],['JustifyCenter'],['JustifyRight']


        //['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
        //    '/',
    ];

    config.filebrowserBrowseUrl = '/ckplugin/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/admin/ckplugin/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = '/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = '/ckplugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = '/admin/ckplugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/ckplugin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

};
