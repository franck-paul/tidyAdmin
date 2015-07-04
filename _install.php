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

	$core->setVersion('tidyAdmin',$new_version);

	return true;
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}
return false;
