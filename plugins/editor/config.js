/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//CKEDITOR.config.toolbar = [
	//   	['Source','-','Preview','Print'],
	//	['Bold','Italic','Underline','Strike','-','Outdent','Indent'],
	//	['Undo','Redo','-','Cut','Copy','Paste','Find','Replace',],
	//	['Image','Flash','-','Link','Unlink','-','Smiley']
	//	,'/',
	//   	['Styles','Format','Font','FontSize'],
	//	['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Blockquote'],
	//   	['Table','SpecialChar','TextColor','BGColor']
	//];
	
	CKEDITOR.config.toolbar = [
	   	['Source','-','Preview'],
		['FontSize'],
		['Bold','Italic','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Image','-','Link','Unlink']
	];
	
	config.height = '300px';

	config.filebrowserBrowseUrl 	 = '../plugins/uploader/browse.php?type=files';
    config.filebrowserImageBrowseUrl = '../plugins/uploader/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = '../plugins/uploader/browse.php?type=flash';
    config.filebrowserUploadUrl 	 = '../plugins/uploader/upload.php?type=files';
    config.filebrowserImageUploadUrl = '../plugins/uploader/upload.php?type=images';
    config.filebrowserFlashUploadUrl = '../plugins/uploader/upload.php?type=flash';
};
