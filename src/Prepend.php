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

use Dotclear\App;
use Dotclear\Core\Process;
use Dotclear\Helper\File\Path;
use Dotclear\Helper\L10n;

class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // User defined Locales
        $l10nFilename = implode(DIRECTORY_SEPARATOR, [App::config()->varRoot(), 'plugins', My::id(), 'admin']);
        if (($file = Path::real($l10nFilename . '.po')) && file_exists($file)) {
            if ($file = Path::real($l10nFilename, false)) {
                L10n::set($file);
            }
        }

        return true;
    }
}
