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

// dead but useful code, in order to have translations
__('Tidy Administration').__('Customize your dotclear administration');

$_menu['System']->addItem(__('Tidy Administration'),
		'plugin.php?p=tidyAdmin',
		urldecode(dcPage::getPF('tidyAdmin/icon.png')),
		preg_match('/plugin.php\?p=tidyAdmin(&.*)?$/',$_SERVER['REQUEST_URI']),
		$core->auth->isSuperAdmin());

/* Register favorite */
$core->addBehavior('adminDashboardFavorites',array('tidyAdminBehaviour','adminDashboardFavorites'));

$core->addBehavior('adminPageHTMLHead',array('tidyAdminBehaviour','adminPageHTMLHead'));

class tidyAdminBehaviour
{
	public static function adminPageHTMLHead()
	{
		global $core;

		// User defined CSS rules
		if (file_exists(dirname(__FILE__).'/css/admin.css')) {
			echo
				dcPage::cssLoad(urldecode(dcPage::getPF(basename(dirname(__FILE__)).'/css/admin.css')))."\n";
		}
		// User defined Javascript
		if (file_exists(dirname(__FILE__).'/js/admin.js')) {
			echo
				dcPage::jsLoad(urldecode(dcPage::getPF(basename(dirname(__FILE__)).'/js/admin.js')))."\n";
		}
	}

	public static function adminDashboardFavorites($core,$favs)
	{
		$favs->register('tidyAdmin', array(
			'title' => __('Tidy Administration'),
			'url' => 'plugin.php?p=tidyAdmin',
			'small-icon' => urldecode(dcPage::getPF('tidyAdmin/icon.png')),
			'large-icon' => urldecode(dcPage::getPF('tidyAdmin/icon-big.png')),
			'permissions' => $core->auth->isSuperAdmin()
		));
	}
}
