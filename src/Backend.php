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

use dcAdmin;
use dcCore;
use dcNsProcess;
use dcPage;

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN');

        // dead but useful code, in order to have translations
        __('Tidy Administration') . __('Customize your dotclear administration');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_SYSTEM]->addItem(
            __('Tidy Administration'),
            'plugin.php?p=tidyAdmin',
            urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
            preg_match('/plugin.php\?p=tidyAdmin(&.*)?$/', $_SERVER['REQUEST_URI']),
            dcCore::app()->auth->isSuperAdmin()
        );

        /* Register favorite */
        dcCore::app()->addBehaviors([
            'adminDashboardFavoritesV2' => [BackendBehaviors::class, 'adminDashboardFavorites'],

            'adminPageHTMLHead' => [BackendBehaviors::class, 'adminPageHTMLHead'],
        ]);

        return true;
    }
}
