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

$part = '';

// Get current content of JS file

$js_file = dirname(__FILE__).'/js/admin.js';
$js_backup_file = dirname(__FILE__).'/js/admin-backup.js';
$js_content = @file_get_contents($js_file);
$js_writable = file_exists($js_file) && is_writable($js_file) && is_writable(dirname($js_file));

// Get demo JS content

$js_demo_file = dirname(__FILE__).'/js/admin-demo.js';
$js_demo_content = @file_get_contents($js_demo_file);

if (!empty($_POST['js'])) {
	// Try to write JS file
	try
	{
		# Write file
		if (!empty($_POST['js_content']))
		{
			$js_content = $_POST['js_content']."\n";
			$fp = @fopen($js_file,'wb');
			if (!$fp) {
				throw new Exception(sprintf(__('Unable to write file %s. Please check your js folder permissions of this plugin.'),$js_file));
			} else {
				fwrite($fp,$js_content);
				fclose($fp);
				if ($fp = @fopen($js_backup_file,'wb')) {
					// Backup file
					fwrite($fp,$js_content);
					fclose($fp);
				}
				dcPage::addSuccessNotice(__('JS supplemental script updated'));
				http::redirect($p_url.'&part=js-editor');
			}
		}
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

// Get current content of CSS file

$css_file = dirname(__FILE__).'/css/admin.css';
$css_backup_file = dirname(__FILE__).'/css/admin-backup.css';
$css_content = @file_get_contents($css_file);
$css_writable = file_exists($css_file) && is_writable($css_file) && is_writable(dirname($css_file));

// Get demo CSS content

$css_demo_file = dirname(__FILE__).'/css/admin-demo.css';
$css_demo_content = @file_get_contents($css_demo_file);

if (!empty($_POST['css'])) {
	// Try to write CSS rule
	try
	{
		# Write file
		if (!empty($_POST['css_content']))
		{
			$css_content = $_POST['css_content']."\n";
			$fp = @fopen($css_file,'wb');
			if (!$fp) {
				throw new Exception(sprintf(__('Unable to write file %s. Please check your css folder permissions of this plugin.'),$css_file));
			} else {
				fwrite($fp,$css_content);
				fclose($fp);
				if ($fp = @fopen($css_backup_file,'wb')) {
					// Backup file
					fwrite($fp,$js_content);
					fclose($fp);
				}
				dcPage::addSuccessNotice(__('CSS supplemental rules updated'));
				http::redirect($p_url.'&part=css-editor');
			}
		}
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

$iconsets_root = dirname(DC_RC_PATH).'/../admin/images/iconset/';
$is_writable = false;

if (!file_exists($iconsets_root)) {
	// Try to create the iconset root folder
	@mkdir($iconsets_root);
}

$excluded_dirs = array('.hg','.hgcheck','.git','.svn');
$iconsets = array();

// Get current list of iconsets
// inactives have their names begining by a . (dash)
if (is_dir($iconsets_root) && is_readable($iconsets_root)) {
	$is_writable = is_writable($iconsets_root);
	if (($d = @dir($iconsets_root)) !== false) {
		while (($entry = $d->read()) !== false) {
			if ($entry != '.' && $entry != '..' && is_dir($iconsets_root.'/'.$entry) && !in_array($entry, $excluded_dirs)) {
				if (substr($entry, 0, 1) == '.') {
					$name = substr($entry,1);
					$enabled = false;
				} else {
					$name = $entry;
					$enabled = true;
				}
				// Try to find a README.md, README.txt or README
				$readme = $freadme = $treadme = '';
				$path = $iconsets_root.'/'.$entry;
				if (($f = @dir($path)) !== false) {
					while (($file = $f->read()) !== false) {
						if ($file != '.' && $file != '..' && !is_dir($path.'/'.$file)) {
							if (in_array(strtolower($file), array('readme.md','readme.txt','readme'))) {
								$freadme = $path.'/'.$file;
								if (is_readable($freadme)) {
									$treadme = file_get_contents($freadme);
									$readme = '<h3>'.sprintf(__('%s Iconset'),$name).'</h3><hr /><p>'.nl2br($treadme,true).'</p>';
									break;
								} else {
									$freadme = '';
								}
							}
						}
					}
				}
				$iconsets[$name] = array(
					'path' => $path,
					'deletable' => $is_writable,
					'deactivable' => $is_writable,
					'name' => $name,
					'fname' => $entry,
					'enabled' => $enabled,
					'freadme' => $freadme,
					'treadme' => $treadme,
					'readme' => $readme
				);
			}
		}
		// Sort array on iconset's name
		ksort($iconsets);
	}
}

$iconset_id = !empty($_POST['iconset_id']) ? $_POST['iconset_id'] : null;

if (is_dir($iconsets_root) && is_readable($iconsets_root)) {

	// Delete iconset
	if ($is_writable && $iconset_id && !empty($_POST['delete'])) {
		try
		{
			files::deltree($iconsets[$iconset_id]['path']);
			dcPage::addSuccessNotice(__('Iconset has been successfully deleted'));
			http::redirect($p_url.'&part=iconset');
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}
	}

	// Deactivate iconset
	if ($is_writable && $iconset_id && !empty($_POST['deactivate'])) {
		try
		{
			rename($iconsets[$iconset_id]['path'],dirname($iconsets[$iconset_id]['path']).'/.'.$iconsets[$iconset_id]['name']);
			dcPage::addSuccessNotice(__('Iconset has been successfully disabled'));
			http::redirect($p_url.'&part=iconset');
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}
	}

	// Activate iconset
	if ($is_writable && $iconset_id && !empty($_POST['activate'])) {
		try
		{
			rename($iconsets[$iconset_id]['path'],dirname($iconsets[$iconset_id]['path']).'/'.$iconsets[$iconset_id]['name']);
			dcPage::addSuccessNotice(__('Iconset has been successfully enabled'));
			http::redirect($p_url.'&part=iconset');
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
		}
	}

	// Iconset upload
	if ($is_writable &&
		((!empty($_POST['upload_pkg']) && !empty($_FILES['pkg_file'])) ||
		 (!empty($_POST['fetch_pkg']) && !empty($_POST['pkg_url']))))
	{
		try
		{
			if (empty($_POST['your_pwd']) || !$core->auth->checkPassword(crypt::hmac(DC_MASTER_KEY,$_POST['your_pwd']))) {
				throw new Exception(__('Password verification failed'));
			}

			if (!empty($_POST['upload_pkg']))
			{
				files::uploadStatus($_FILES['pkg_file']);

				$dest = $iconsets_root.$_FILES['pkg_file']['name'];
				if (!move_uploaded_file($_FILES['pkg_file']['tmp_name'],$dest)) {
					throw new Exception(__('Unable to move uploaded file.'));
				}
			}
			else
			{
				$url = urldecode($_POST['pkg_url']);
				$dest = $iconsets_root.basename($url);

				try
				{
					$client = netHttp::initClient($url,$path);
					$client->setUserAgent('Dotclear - http://www.dotclear.org/');
					$client->useGzip(false);
					$client->setPersistReferers(false);
					$client->setOutput($dest);
					$client->get($path);
				}
				catch( Exception $e)
				{
					throw new Exception(__('An error occurred while downloading the file.'));
				}

				unset($client);
			}

			$preserve = !empty($_POST['pkg_preserve']);
			$ret_code = libIconset::installIconset($dest,$preserve);
			if ($ret_code == '2') {
				dcPage::addSuccessNotice(__('Iconset has been successfully updated'));
			} else {
				dcPage::addSuccessNotice(__('Iconset has been successfully installed'));
			}
			http::redirect($p_url.'&part=iconset-install');
		}
		catch (Exception $e)
		{
			$core->error->add($e->getMessage());
			$part = 'iconset-install';
		}
	}
}

if ($part == '') {
	if (!empty($_GET['part'])) {
		if (in_array($_GET['part'], array('iconset','iconset-install','css-editor','js-editor'))) {
			$part = $_GET['part'];
		}
	}
}
if ($part == '') {
	$part = 'iconset';
}

# Get interface setting
$core->auth->user_prefs->addWorkspace('interface');
$user_ui_colorsyntax = $core->auth->user_prefs->interface->colorsyntax;
$user_ui_colorsyntax_theme = $core->auth->user_prefs->interface->colorsyntax_theme;
?>

<html>
<head>
	<title><?php echo __('Tidy administration settings'); ?></title>
<?php
	echo
	dcPage::cssLoad(urldecode(dcPage::getPF('tidyAdmin/style.css')),'screen',$core->getVersion('tidyAdmin')).
	dcPage::cssLoad(urldecode(dcPage::getPF('tidyAdmin/codemirror.css')),'screen',$core->getVersion('tidyAdmin')).
	dcPage::jsModal().
	dcPage::jsConfirmClose('css-form').
	dcPage::jsPageTabs($part).
	'<script type="text/javascript">'."\n".
	"//<![CDATA[\n".
		dcPage::jsVar('dotclear.msg.confirm_delete_iconset',__('Are you sure you want to delete "%s" iconset?')).
	"\n//]]>\n".
	"</script>\n".
	dcPage::jsLoad(urldecode(dcPage::getPF('tidyAdmin/js/iconset.js')),$core->getVersion('tidyAdmin')).
	dcPage::jsCodeMirror($user_ui_colorsyntax_theme,false,array('css','javascript'));
?>
</head>

<body>
<?php
echo dcPage::breadcrumb(
	array(
		__('System') => '',
		__('Tidy administration settings') => ''
	));
echo dcPage::notices();
?>

<div id="iconset"  class="multi-part" title="<?php echo __('Iconset management'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Iconset management'); ?></h3>
<?php

	// Iconset activation/deactivation/desintallation form
	if (count($iconsets)) {
		echo
		'<div class="table-outer"><table class="iconset_list"><caption class="as_h3">'.__('Iconset list').'</caption>'.
		'<thead>'.
		'<tr>'."\n".
		'  <th>'.__('Iconset').'</th>'."\n".
		'  <th class="nowrap">'.__('Status').'</th>'."\n".
		'  <th class="nowrap">'.__('Action').'</th>'."\n".
		'</tr>'."\n".
		'</thead>'."\n".
		'<tbody>'."\n";
		foreach ($iconsets as $k => $v) {
			echo
			'<tr class="line">'.
				'<td scope="row" class="maximal">'.$v['name'].
					($v['freadme'] != '' ?
						' (<a href="#" class="iconset-readme" data-readme="'.$v['readme'].'" title="'.$v['treadme'].'" />'.__('Informations').'</a>)' :
						'').
				'</td>'.
				'<td class="minimal">'.($v['enabled'] ?
					'<img src="images/check-on.png" title="'.__('Enabled').'" alt="'.__('Enabled').'" />' :
					'<img src="images/check-off.png" title="'.__('Disabled').'" alt="'.__('Disabled').'" />').
				'</td>'.
				'<td class="nowrap action">';
				echo
					'<form action="'.$p_url.'" method="post">'.
					'<div>'.
					$core->formNonce().
					form::hidden(array('iconset_id'),html::escapeHTML($v['name']));

					if ($v['enabled'] && $v['deactivable']) {
						echo '<input type="submit" name="deactivate" value="'.__('Deactivate').'" /> ';
					} elseif (!$v['enabled'] && $v['deactivable']) {
						echo '<input type="submit" name="activate" value="'.__('Activate').'" /> ';
					}
					if ($v['deletable']) {
						echo '<input type="submit" class="delete" name="delete" value="'.__('Delete').'" />';
					}

				echo
					'</div>'.
					'</form>'.
				'</td>'.
			'</tr>'."\n";
		}
		echo '</tbody></table></div>';
	} else {
		echo '<p class="info">'.__('No iconset installed.').'</p>';
	}
?>
</div>

<div id="iconset-install"  class="multi-part" title="<?php echo __('Iconset installation or update'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Iconset installation'); ?></h3>
<?php
	if ($is_writable) {
		# 'Upload iconset' form
		echo
		'<form method="post" action="'.$p_url.'" id="uploadpkg" enctype="multipart/form-data" class="fieldset">'.
		'<h3>'.__('Upload a zip file').'</h3>'.
		'<p class="field"><label for="pkg_file" class="classic required"><abbr title="'.__('Required field').'">*</abbr> '.__('Iconset zip file:').'</label> '.
		'<input type="file" id="pkg_file" name="pkg_file" /></p>'.
		'<p class="field"><label for="your_pwd1" class="classic required"><abbr title="'.__('Required field').'">*</abbr> '.__('Your password:').'</label> '.
		form::password(array('your_pwd','your_pwd1'),20,255).'</p>'.
		'<p><label for="pkg_zip_preserve" class="classic">'.
		form::checkbox(array('pkg_preserve','pkg_zip_preserve'),1,true).' '.__('Preserve existing folders and files not in zip file').'</label></p>'.
		'<p><input type="submit" name="upload_pkg" value="'.__('Upload iconset').'" />'.
		$core->formNonce().
		'</p>'.
		'</form>';

		# 'Fetch iconset' form
		echo
		'<form method="post" action="'.$p_url.'" id="fetchpkg" class="fieldset">'.
		'<h3>'.__('Download a zip file').'</h3>'.
		'<p class="field"><label for="pkg_url" class="classic required"><abbr title="'.__('Required field').'">*</abbr> '.__('Iconset zip file URL:').'</label> '.
		form::field(array('pkg_url','pkg_url'),40,255).'</p>'.
		'<p class="field"><label for="your_pwd2" class="classic required"><abbr title="'.__('Required field').'">*</abbr> '.__('Your password:').'</label> '.
		form::password(array('your_pwd','your_pwd2'),20,255).'</p>'.
		'<p><label for="pkg_url_preserve" class="classic">'.
		form::checkbox(array('pkg_preserve','pkg_url_preserve'),1,true).' '.__('Preserve existing folders and files not in zip file').'</label></p>'.
		'<p><input type="submit" name="fetch_pkg" value="'.__('Download iconset').'" />'.
		$core->formNonce().
		'</p>'.
		'</form>';

		echo
		'<p class="info">'.__('Note: An iconset must be enabled in order to be updated.').'</p>';
	} else {
		echo
		'<p class="static-msg">'.
		__('To enable iconset installation, please give write access to your iconset directory.').
		'</p>';
	}
?>
</div>

<div id="css-editor"  class="multi-part" title="<?php echo __('Supplemental CSS editor'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Supplemental CSS editor'); ?></h3>
<?php
{
	echo
	'<form id="css-form" action="'.$p_url.'" method="post">'.
	'<p>'.form::textarea('css_content',72,25,html::escapeHTML($css_content),'maximal','',!$css_writable).'</p>';
	if ($css_writable)
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

	// Display demo CSS content
	'<p>'.__('Sample CSS:').'</p>'.
	'<p>'.form::textarea('css_demo_content',72,25,html::escapeHTML($css_demo_content),'maximal','',false,'readonly="true"').'</p>';

	'</form>';
}
?>
</div>

<div id="js-editor"  class="multi-part" title="<?php echo __('Supplemental JS editor'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Supplemental JS editor'); ?></h3>
<?php
{
	echo
	'<form id="js-form" action="'.$p_url.'" method="post">'.
	'<p>'.form::textarea('js_content',72,25,html::escapeHTML($js_content),'maximal','',!$js_writable).'</p>';
	if ($js_writable)
	{
		echo
		'<p><input type="submit" name="js" value="'.__('Save').' (s)" accesskey="s" /> '.
		$core->formNonce().
		'</p>';
	}
	else
	{
		echo '<p>'.sprintf(__('The %s file is not writable. Please check the css folder permissions of this plugin.'),$js_file).'</p>';
	}
	echo
	'<p class="info">'.__('Note: this supplemental JS script will surcharge the default JS scripts.').'</p>'.

	// Display demo JS content
	'<p>'.__('Sample JS:').'</p>'.
	'<p>'.form::textarea('js_demo_content',72,25,html::escapeHTML($js_demo_content),'maximal','',false,'readonly="true"').'</p>';

	'</form>';
}
?>
</div>

<script type="text/javascript">
//<![CDATA[
	var editor_css = CodeMirror.fromTextArea(css_content,
		{mode: "css" <?php echo ($user_ui_colorsyntax_theme != '' ? ',theme: "'.$user_ui_colorsyntax_theme.'"' : '') ?>});
	var editor_js = CodeMirror.fromTextArea(js_content,
		{mode: "javascript" <?php echo ($user_ui_colorsyntax_theme != '' ? ',theme: "'.$user_ui_colorsyntax_theme.'"' : '') ?>});
//]]>
</script>
</body>
</html>
