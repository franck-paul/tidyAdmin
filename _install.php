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

$new_version = $core->plugins->moduleInfo('tidyAdmin','version');
$old_version = $core->getVersion('tidyAdmin');

if (version_compare($old_version,$new_version,'>=')) return;

try
{
	if (version_compare(DC_VERSION,'2.6','<'))
	{
		throw new Exception('Tidy Admin requires Dotclear 2.6 or higher');
	}

	$css_file = dirname(__FILE__).'/css/admin.css';
	if (!file_exists($css_file)) {
		// Create admin.css file from demo
		$css_demo_file = dirname(__FILE__).'/css/admin-demo.css';
		$css_readable = file_exists($css_demo_file) && is_readable($css_demo_file) && is_readable(dirname($css_demo_file));
		if ($css_readable) {
			$css_content = file_get_contents($css_demo_file);
			$css_writable = is_writable(dirname($css_file));
			if ($css_writable) {
				$fp = @fopen($css_file,'wb');
				if ($fp) {
					fwrite($fp,$css_content);
					fclose($fp);
				}
			}
		}
	}

	$core->setVersion('tidyAdmin',$new_version);

	return true;
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}
return false;
