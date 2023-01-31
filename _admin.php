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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

// dead but useful code, in order to have translations
__('Tidy Administration') . __('Customize your dotclear administration');

dcCore::app()->menu[dcAdmin::MENU_SYSTEM]->addItem(
    __('Tidy Administration'),
    'plugin.php?p=tidyAdmin',
    urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
    preg_match('/plugin.php\?p=tidyAdmin(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->isSuperAdmin()
);

class tidyAdminBehaviour
{
    public static function adminPageHTMLHead()
    {
        // Reduce home button
        if (dcCore::app()->auth->user_prefs->interface->minidcicon) {
            echo
                dcPage::cssModuleLoad('tidyAdmin/css/dcicon.css', 'screen', dcCore::app()->getVersion('tidyAdmin')) . "\n" .
                dcPage::jsModuleLoad('tidyAdmin/js/dcicon.js', dcCore::app()->getVersion('tidyAdmin')) . "\n";
        }
        // Load search form (menu) repositioning helper
        if (dcCore::app()->auth->user_prefs->interface->movesearchmenu) {
            echo
                dcPage::cssModuleLoad('tidyAdmin/css/search_menu.css', 'screen', dcCore::app()->getVersion('tidyAdmin')) . "\n" .
                dcPage::jsModuleLoad('tidyAdmin/js/search_menu.js', dcCore::app()->getVersion('tidyAdmin')) . "\n";
        }
        // Load search form (media) repositioning helper
        if (dcCore::app()->auth->user_prefs->interface->clonesearchmedia) {
            echo
                dcPage::cssModuleLoad('tidyAdmin/css/search_media.css', 'screen', dcCore::app()->getVersion('tidyAdmin')) . "\n" .
                dcPage::jsModuleLoad('tidyAdmin/js/search_media.js', dcCore::app()->getVersion('tidyAdmin')) . "\n";
        }
        // Add hover detection on collapser
        if (dcCore::app()->auth->user_prefs->interface->hovercollapser) {
            echo
                dcPage::jsModuleLoad('tidyAdmin/js/hover_collapser.js', dcCore::app()->getVersion('tidyAdmin')) . "\n";
        }

        // User defined CSS rules
        if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.css')) {
            echo dcPage::cssLoad(urldecode(dcPage::getVF('plugins/tidyAdmin/admin.css'))) . "\n";
        }
        // User defined Javascript
        if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.js')) {
            echo dcPage::jsLoad(urldecode(dcPage::getVF('plugins/tidyAdmin/admin.js'))) . "\n";
        }
    }

    public static function adminDashboardFavorites($favs)
    {
        $favs->register('tidyAdmin', [
            'title'      => __('Tidy Administration'),
            'url'        => 'plugin.php?p=tidyAdmin',
            'small-icon' => urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
            'large-icon' => urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
        ]);
    }
}

/* Register favorite */
dcCore::app()->addBehaviors([
    'adminDashboardFavoritesV2' => [tidyAdminBehaviour::class, 'adminDashboardFavorites'],

    'adminPageHTMLHead'         => [tidyAdminBehaviour::class, 'adminPageHTMLHead'],
]);
