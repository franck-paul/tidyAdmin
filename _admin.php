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

$_menu['System']->addItem(__('Tidy Administration'),'plugin.php?p=tidyAdmin','index.php?pf=tidyAdmin/icon.png',
		preg_match('/plugin.php\?p=tidyAdmin(&.*)?$/',$_SERVER['REQUEST_URI']),
		$core->auth->isSuperAdmin());

$core->addBehavior('adminDashboardFavs',array('tidyAdminBehaviour','adminDashboardFavs'));
$core->addBehavior('adminPageHTMLHead',array('tidyAdminBehaviour','adminCssLink'));

class tidyAdminBehaviour
{
	public static function adminCssLink()
	{
		global $core;

		echo
			'<link rel="stylesheet" href="'.
			$core->blog->getQmarkURL().'pf='.basename(dirname(__FILE__)).'/css/admin.css'.
			'" type="text/css" media="screen" />'."\n";
	}

	public static function adminDashboardFavs($core,$favs)
	{
		$favs['tidyAdmin'] = new ArrayObject(array('tidyAdmin','Tidy Administration','plugin.php?p=tidyAdmin',
			'index.php?pf=tidyAdmin/icon.png','index.php?pf=tidyAdmin/icon-big.png',
			null,null,null));
	}
}
