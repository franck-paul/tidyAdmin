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

// Get current content of CSS file

$css_file = dirname(__FILE__).'/css/admin.css';
$css_content = file_get_contents($css_file);
$css_writeable = file_exists($css_file) && is_writable($css_file) && is_writable(dirname($css_file));
$part = '';

if (!empty($_POST['css'])) {
	$part = 'css-editor';
	try
	{
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
				http::redirect($p_url.'&css=1');
			}
		}
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

// Get current list of iconsets
// inactives have their names begining by a . (dash)
ob_start();
$iconsets = array();
$iconsets_root = dirname(DC_RC_PATH).'/../admin/images/iconset/';
$excluded_dirs = array('.hg','.git','.svn');
var_dump($iconsets_root);
if (is_dir($iconsets_root) && is_readable($iconsets_root)) {
	if (($d = @dir($iconsets_root)) !== false) {
		while (($entry = $d->read()) !== false) {
			var_dump($entry);
			if ($entry != '.' && $entry != '..' && is_dir($iconsets_root.'/'.$entry) && !in_array($entry, $excluded_dirs)) {
				if (substr($entry, 0, 1) == '.') {
					$name = substr($entry,1);
					$enabled = false;
				} else {
					$name = $entry;
					$enabled = true;
				}
				// Try to find a README.md, README.txt or README
				$readme = '';
				$freadme = '';
				$path = $iconsets_root.'/'.$entry;
				if (($f = @dir($path)) !== false) {
					while (($file = $f->read()) !== false) {
						if ($file != '.' && $file != '..' && !is_dir($path.'/'.$file)) {
							if (in_array(strtolower($file), array('readme.md','readme.txt','readme'))) {
								$freadme = $path.'/'.$file;
								if (is_readable($freadme)) {
									$readme = file_get_contents($freadme);
									break;
								} else {
									$freadme = '';
								}
							}
						}
					}
				}
				$iconsets[$name] = array(
					'name' => $name,
					'fname' => $entry,
					'enabled' => $enabled,
					'freadme' => $freadme,
					'readme' => html::escapeHTML($readme)
				);
				var_dump($iconsets[$name]);
			}
		}
		// Sort array on iconset's name
		ksort($iconsets);
		var_dump($iconsets);
	}
}
$dump = ob_get_clean();

if ($part == '') {
	$part = !empty($_GET['part']) && $_GET['part'] == 'iconset' ? 'iconset' : 'css-editor';
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
	<?php echo dcPage::jsPageTabs($part); ?>
</head>

<body>
<?php
echo dcPage::breadcrumb(
	array(
		__('System') => '',
		__('Tidy administration settings') => ''
	));
if (!empty($_GET['css'])) {
	dcPage::success(__('CSS supplemental rules updated'));
}
?>

<?php
if ($dump != '') {
//	echo '<div id="dump">'.$dump.'</div>';
}
?>

<div id="css-editor"  class="multi-part" title="<?php echo __('Supplemental CSS editor'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Supplemental CSS editor'); ?></h3>
<?php
{
	echo
	'<form id="file-form" action="'.$p_url.'" method="post">'.
	'<p>'.form::textarea('css_content',72,25,html::escapeHTML($css_content),'maximal','',!$css_writeable).'</p>';
	if ($css_writeable)
	{
		echo
		'<p><input type="submit" name="css" value="'.__('Save').' (s)" accesskey="s" /> '.
		$core->formNonce().
		'</p>';
	}
	else
	{
		echo '<p>'.sprintf(__('The %s file is not writable. Please check the css folder permissions of this plugin.'),$css_file).'</p>';
	}
	echo
	'<p class="info">'.__('Note: this supplemental CSS rules will surcharge the default CSS rules.').'</p>'.
	'</form>';
}
?>
</div>

<div id="iconset"  class="multi-part" title="<?php echo __('Iconset management'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Iconset management'); ?></h3>
<?php
	echo
	'<form id="iconset-form" action="'.$p_url.'" method="post">';
	// Display list of iconset
	if (count($iconsets)) {
		$nb_actives = $nb_inactives = 0;
		echo
		'<div class="table-outer"><table id="iconset_list"><caption class="as_h3">'.__('Iconset list').'</caption>'.
		'<thead>'.
		'<tr>'."\n".
		'  <th class="nowrap">'.__('Name').'</th>'."\n".
		'  <th>'.__('Folder name').'</th>'."\n".
		'  <th>'.__('Enabled').'</th>'."\n".
		'  <th class="maximalx">'.__('Readme').'</th>'."\n".
		'</tr>'."\n".
		'</thead>'."\n".
		'<tbody>';
		foreach ($iconsets as $k => $v) {
			echo
			'<tr class="line">'.
				'<td scope="row">'.form::checkbox(array('iconset[]'),$v['fname'],'','','',false,'title="'.__('select').' '.$v['name'].'"').' '.
				$v['name'].'</td>'.
				'<td>'.$v['fname'].'</td>'.
				'<td>'.($v['enabled'] ? __('yes') : __('no')).'</td>'.
				'<td>'.$v['freadme'].'</td>'.
			'</tr>';
			if ($v['enabled']) {
				$nb_actives++;
			} else {
				$nb_inactives++;
			}
		}
		echo '</tbody></table></div>';
		echo
		'<p>'.
		'<input type="submit" name="enable" value="'.__('Enable').'" '.($nb_inactives ? '' : 'disabled="disabled"').' />'.' '.
		'<input type="submit" name="disable" value="'.__('Disable').'" '.($nb_actives ? '' : 'disabled="disabled"').' />'.
		'</p>';
	}
	echo
	'<p>'.$core->formNonce().'</p>'.
	'</form>';
?>
</div>

<script type="text/javascript">
//<![CDATA[
	var editor = CodeMirror.fromTextArea(css_content, {mode: "css"});
//]]>
</script>
</body>
</html>