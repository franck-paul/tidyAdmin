<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of tidyAdmin, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$css_file = dirname(__FILE__).'/css/admin.css';
$css_content = '';
$css_writeable = false;

try
{
	$css_content = file_get_contents($css_file);
	$css_writeable = file_exists($css_file) && is_writable($css_file) && is_writable(dirname($css_file));
	# Write file
	if (!empty($_POST['css_content']))
	{
		$css_content = $_POST['css_content'];
		$fp = @fopen($css_file,'wb');
		if (!$fp) {
			throw new Exception(sprintf(__('Unable to write file %s. Please check your css folder permissions of this plugin.'),$css_file));
		} else {
			fwrite($fp,$css_content);
			fclose($fp);

		}
	}
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}
?>

<html>
<head>
	<title><?php echo __('Tidy administration settings'); ?></title>
	<link rel="stylesheet" type="text/css" href="index.php?pf=tidyAdmin/style.css" />
	<link rel="stylesheet" type="text/css" href="index.php?pf=tidyAdmin/codemirror/codemirror.css" />
	<link rel="stylesheet" type="text/css" href="index.php?pf=tidyAdmin/codemirror.css" />
	<script type="text/JavaScript" src="index.php?pf=tidyAdmin/codemirror/codemirror.js"></script>
	<script type="text/JavaScript" src="index.php?pf=tidyAdmin/codemirror/css.js"></script>
</head>

<body>
<?php
echo dcPage::breadcrumb(
	array(
		__('System') => '',
		'<span class="page-title">'.__('Tidy administration settings').'</span>' => ''
	));
?>

<div id="css-editor">
<?php
{
	echo
	'<form id="file-form" action="'.$p_url.'" method="post">'.
	'<fieldset><legend>'.__('Supplemental CSS editor').'</legend>'.
	'<p>'.form::textarea('css_content',72,25,html::escapeHTML($css_content),'maximal','',!$css_writeable).'</p>';
	if ($css_writeable)
	{
		echo
		'<p><input type="submit" name="write" value="'.__('Save').' (s)" accesskey="s" /> '.
		$core->formNonce().
		'</p>';
	}
	else
	{
		echo '<p>'.sprintf(__('The %s file is not writable. Please check the css folder permissions of this plugin.'),$css_file).'</p>';
	}
	echo
	'<p class="info">'.__('Note: this supplemental CSS rules will surcharge the default CSS rules.').'</p>'.
	'</fieldset></form>';
}
?>
</div>

<script>
	var editor = CodeMirror.fromTextArea(css_content, {mode: "css"});
</script>
</body>
</html>