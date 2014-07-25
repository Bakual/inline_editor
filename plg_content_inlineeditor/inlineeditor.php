<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.Inlineeditor
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 **/

defined('_JEXEC') or die();

/**
 * Plug-in to show an inline editor
 *
 * @since  3.4
 */
class PlgContentInlineeditor extends JPlugin
{
	/**
	 * Plugin that shows an inline editor
	 *
	 * @param   string  $context   The context of the content being passed to the plugin.
	 * @param   object  &$article  The article object.  Note $article->text is also available
	 * @param   object  &$params   The article params
	 * @param   int     $page      The 'page' number
	 *
	 * @return void
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		JHtmlJquery::framework();
		JHtml::script('media/editors/tinymce/tinymce.min.js');
		$targeturl = 'index.php?option=com_ajax&plugin=inlineeditor&format=json&group=content';
		$init = 'tinymce.init({
					selector: ".editable.editable-simple",
					inline: true,
					plugins: ["save"],
					toolbar: "save | undo redo",
					menubar: false,
					save_onsavecallback: function() {
						jQuery.ajax("' . $targeturl . '",
							{
								type: "POST",
								data: {text: this.getContent()},
							}
						);
					}
				});
				tinymce.init({
					selector: ".editable.editable-full",
					inline: true,
					plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen",
						"insertdatetime media table contextmenu paste save"
					],
					toolbar: "save | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
					save_onsavecallback: function() {console.log(this.getContent());}
				});';
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($init);
	}

	public function onAjaxInlineeditor()
	{
		return true;
	}
}
