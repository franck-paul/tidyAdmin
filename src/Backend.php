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

class Backend extends dcNsProcess
{
    protected static $init = false; /** @deprecated since 2.27 */
    public static function init(): bool
    {
        static::$init = My::checkContext(My::BACKEND);

        // dead but useful code, in order to have translations
        __('Tidy Administration') . __('Customize your dotclear administration');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        if (My::checkContext(My::MENU)) {
            // Add menu
            dcCore::app()->menu[dcAdmin::MENU_SYSTEM]->addItem(
                __('Tidy Administration'),
                My::makeUrl(),
                My::icons(),
                preg_match(My::urlScheme(), $_SERVER['REQUEST_URI']),
                My::checkContext(My::MENU)
            );

            // Register favorite
            dcCore::app()->addBehaviors([
                'adminDashboardFavoritesV2' => [BackendBehaviors::class, 'adminDashboardFavorites'],
            ]);
        }

        dcCore::app()->addBehaviors([
            'adminPageHTMLHead' => [BackendBehaviors::class, 'adminPageHTMLHead'],
        ]);

        return true;
    }
}
