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
use Dotclear\Core\Backend\Favorites;
use Dotclear\Core\Backend\Page;
use Dotclear\Helper\File\Path;

class BackendBehaviors
{
    public static function adminPageHTMLHead(): string
    {
        // Reduce home button
        if (App::auth()->prefs()->interface->minidcicon) {
            echo
                My::cssLoad('dcicon.css') .
                My::jsLoad('dcicon.js');
        }
        // Load search form (menu) repositioning helper
        if (App::auth()->prefs()->interface->movesearchmenu) {
            echo
                My::cssLoad('search_menu.css') .
                My::jsLoad('search_menu.js');
        }
        // Load search form (media) repositioning helper
        if (App::auth()->prefs()->interface->clonesearchmedia) {
            echo
                My::cssLoad('search_media.css') .
                My::jsLoad('search_media.js');
        }
        // Add hover detection on collapser
        if (App::auth()->prefs()->interface->hovercollapser) {
            echo
                My::jsLoad('hover_collapser.js');
        }
        // Move plugin settings link to top
        if (App::auth()->prefs()->interface->pluginconfig) {
            echo
                My::jsLoad('plugin_config.js');
        }

        // User defined head directives
        if (file_exists(Path::real(DC_VAR) . '/plugins/' . My::id() . '/admin.html')) {
            echo file_get_contents(Path::real(DC_VAR) . '/plugins/' . My::id() . '/admin.html') . "\n";
        }
        // User defined CSS rules
        if (file_exists(Path::real(DC_VAR) . '/plugins/' . My::id() . '/admin.css')) {
            echo Page::cssLoad(urldecode(Page::getVF('plugins/' . My::id() . '/admin.css'))) . "\n";
        }
        // User defined Javascript
        if (file_exists(Path::real(DC_VAR) . '/plugins/' . My::id() . '/admin.js')) {
            echo Page::jsLoad(urldecode(Page::getVF('plugins/' . My::id() . '/admin.js'))) . "\n";
        }

        return '';
    }

    public static function adminDashboardFavorites(Favorites $favs): string
    {
        $favs->register(My::id(), [
            'title'      => __('Tidy Administration'),
            'url'        => My::manageUrl(),
            'small-icon' => My::icons(),
            'large-icon' => My::icons(),
        ]);

        return '';
    }
}
