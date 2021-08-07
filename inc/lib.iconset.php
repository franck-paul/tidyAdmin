<?php
/**
 * @brief tidyAdmin, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class libIconset
{
    public static function installIconset($zip_file, $preserve = true)
    {
        $zip = new fileUnzip($zip_file);
        $zip->getList(false, '#(^|/)(__MACOSX|\.hg.*|\.git.*|\.svn|\.DS_Store|\.directory|Thumbs\.db)(/|$)#');

        $zip_root_dir = $zip->getRootDir();
        if ($zip_root_dir != false) {
            $target      = dirname($zip_file);
            $destination = $target . '/' . $zip_root_dir;
        } else {
            $target      = dirname($zip_file) . '/' . preg_replace('/\.([^.]+)$/', '', basename($zip_file));
            $destination = $target;
        }

        if ($zip->isEmpty()) {
            $zip->close();
            unlink($zip_file);

            throw new Exception(__('Empty module zip file.'));
        }

        $ret_code = 1; // Installation done
        if (is_dir($destination)) {
            // Update iconset
            if (!$preserve) {
                // Delete old iconset
                if (!files::deltree($destination)) {
                    throw new Exception(__('An error occurred during iconset deletion.'));
                }
            }
            $ret_code = 2; // Update done
        }
        $zip->unzipAll($target);
        $zip->close();
        unlink($zip_file);

        return $ret_code;
    }
}
