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
declare(strict_types=1);

namespace Dotclear\Plugin\tidyAdmin;

use Autoloader;
use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;
use Dotclear\Helper\File\Path;

class Prepend
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // JS/CSS Minifier
        Autoloader::me()->addNamespace('JShrink', implode(DIRECTORY_SEPARATOR, [
            My::path(),
            'libs',
            'tedious',
            'JShrink',
            'src',
            'JShrink',
        ]));

        // User defined Locales
        $l10nFilename = implode(DIRECTORY_SEPARATOR, [App::config()->varRoot(), 'plugins', My::id(), 'admin']);
        $file         = Path::real($l10nFilename . '.po');
        if ($file !== false && file_exists($file)) {
            $file = Path::real($l10nFilename, false);
            if ($file !== false) {
                App::lang()->set($file);
            }
        }

        return true;
    }
}
