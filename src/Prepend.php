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

use dcNsProcess;
use Dotclear\Helper\File\Path;
use Dotclear\Helper\L10n;

class Prepend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        // User defined Locales
        $l10nFilename = implode(DIRECTORY_SEPARATOR, [DC_VAR, 'plugins', My::id(), 'admin']);
        if (file_exists(Path::real($l10nFilename . '.po'))) {
            L10n::set(Path::real($l10nFilename, false));
        }

        return true;
    }
}