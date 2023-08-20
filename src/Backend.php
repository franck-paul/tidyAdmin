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

use dcCore;
use Dotclear\Core\Backend\Menus;
use Dotclear\Core\Process;

class Backend extends Process
{
    public static function init(): bool
    {
        // dead but useful code, in order to have translations
        __('Tidy Administration') . __('Customize your dotclear administration');

        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        if (My::checkContext(My::MENU)) {
            // Add menu
            dcCore::app()->admin->menus[Menus::MENU_SYSTEM]->addItem(
                __('Tidy Administration'),
                My::manageUrl(),
                My::icons(),
                preg_match(My::urlScheme(), $_SERVER['REQUEST_URI']),
                My::checkContext(My::MENU)
            );

            // Register favorite
            dcCore::app()->addBehaviors([
                'adminDashboardFavoritesV2' => BackendBehaviors::adminDashboardFavorites(...),
            ]);
        }

        dcCore::app()->addBehaviors([
            'adminPageHTMLHead' => BackendBehaviors::adminPageHTMLHead(...),
        ]);

        return true;
    }
}
