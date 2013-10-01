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

class libIconset
{
	public static function installIconset($zip_file,$preserve=true)
	{
		$zip = new fileUnzip($zip_file);
		$zip->getList(false,'#(^|/)(__MACOSX|\.hg|\.git|\.svn|\.DS_Store|\.directory|Thumbs\.db)(/|$)#');

		$zip_root_dir = $zip->getRootDir();
		if ($zip_root_dir != false) {
			$target = dirname($zip_file);
			$destination = $target.'/'.$zip_root_dir;
		} else {
			$target = dirname($zip_file).'/'.preg_replace('/\.([^.]+)$/','',basename($zip_file));
			$destination = $target;
		}

		if ($zip->isEmpty()) {
			$zip->close();
			unlink($zip_file);
			throw new Exception(__('Empty module zip file.'));
		}

		$ret_code = 1;	// Installation done
		if (is_dir($destination))
		{
			// Update iconset
			if (!$preserve) {
				// Delete old iconset
				if (!files::deltree($destination)) {
					throw new Exception(__('An error occurred during iconset deletion.'));
				}
			}
			$ret_code = 2;	// Update done
		}
		$zip->unzipAll($target);
		$zip->close();
		unlink($zip_file);
		return $ret_code;
	}
}
