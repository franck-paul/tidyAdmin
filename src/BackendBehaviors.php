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
use dcFavorites;
use dcPage;
use Dotclear\Helper\File\Path;

class BackendBehaviors
{
    public static function adminPageHTMLHead()
    {
        // Reduce home button
        if (dcCore::app()->auth->user_prefs->interface->minidcicon) {
            echo
                dcPage::cssModuleLoad(My::id() . '/css/dcicon.css', 'screen', dcCore::app()->getVersion(My::id() . '')) . "\n" .
                dcPage::jsModuleLoad(My::id() . '/js/dcicon.js', dcCore::app()->getVersion(My::id() . '')) . "\n";
        }
        // Load search form (menu) repositioning helper
        if (dcCore::app()->auth->user_prefs->interface->movesearchmenu) {
            echo
                dcPage::cssModuleLoad(My::id() . '/css/search_menu.css', 'screen', dcCore::app()->getVersion(My::id() . '')) . "\n" .
                dcPage::jsModuleLoad(My::id() . '/js/search_menu.js', dcCore::app()->getVersion(My::id() . '')) . "\n";
        }
        // Load search form (media) repositioning helper
        if (dcCore::app()->auth->user_prefs->interface->clonesearchmedia) {
            echo
                dcPage::cssModuleLoad(My::id() . '/css/search_media.css', 'screen', dcCore::app()->getVersion(My::id() . '')) . "\n" .
                dcPage::jsModuleLoad(My::id() . '/js/search_media.js', dcCore::app()->getVersion(My::id() . '')) . "\n";
        }
        // Add hover detection on collapser
        if (dcCore::app()->auth->user_prefs->interface->hovercollapser) {
            echo
                dcPage::jsModuleLoad(My::id() . '/js/hover_collapser.js', dcCore::app()->getVersion(My::id() . '')) . "\n";
        }
        // Move plugin settings link to top
        if (dcCore::app()->auth->user_prefs->interface->pluginconfig) {
            echo
                dcPage::jsModuleLoad(My::id() . '/js/plugin_config.js', dcCore::app()->getVersion(My::id() . '')) . "\n";
        }

        // User defined CSS rules
        if (file_exists(Path::real(DC_VAR) . '/plugins/' . My::id() . '/admin.css')) {
            echo dcPage::cssLoad(urldecode(dcPage::getVF('plugins/' . My::id() . '/admin.css'))) . "\n";
        }
        // User defined Javascript
        if (file_exists(Path::real(DC_VAR) . '/plugins/' . My::id() . '/admin.js')) {
            echo dcPage::jsLoad(urldecode(dcPage::getVF('plugins/' . My::id() . '/admin.js'))) . "\n";
        }
    }

    public static function adminDashboardFavorites(dcFavorites $favs)
    {
        $favs->register(My::id(), [
            'title'      => __('Tidy Administration'),
            'url'        => 'plugin.php?p=' . My::id(),
            'small-icon' => urldecode(dcPage::getPF(My::id() . '/icon.svg')),
            'large-icon' => urldecode(dcPage::getPF(My::id() . '/icon.svg')),
        ]);
    }
}
