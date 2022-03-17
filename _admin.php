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

$_menu['System']->addItem(
    __('Tidy Administration'),
    'plugin.php?p=tidyAdmin',
    urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
    preg_match('/plugin.php\?p=tidyAdmin(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->isSuperAdmin()
);

/* Register favorite */
$core->addBehavior('adminDashboardFavorites', ['tidyAdminBehaviour', 'adminDashboardFavorites']);

$core->addBehavior('adminPageHTMLHead', ['tidyAdminBehaviour', 'adminPageHTMLHead']);

class tidyAdminBehaviour
{
    public static function adminPageHTMLHead()
    {
        global $core;

        // Load search form repositioning helper
        $core->auth->user_prefs->addWorkspace('interface');
        if ($core->auth->user_prefs->interface->minidcicon) {
            echo
                dcPage::cssModuleLoad('tidyAdmin/css/dcicon.css', 'screen', $core->getVersion('tidyAdmin')) . "\n" .
                dcPage::jsModuleLoad('tidyAdmin/js/dcicon.js', $core->getVersion('tidyAdmin')) . "\n";
        }
        if ($core->auth->user_prefs->interface->movesearchmenu) {
            echo
                dcPage::cssModuleLoad('tidyAdmin/css/search_menu.css', 'screen', $core->getVersion('tidyAdmin')) . "\n" .
                dcPage::jsModuleLoad('tidyAdmin/js/search_menu.js', $core->getVersion('tidyAdmin')) . "\n";
        }
        if ($core->auth->user_prefs->interface->clonesearchmedia) {
            echo
                dcPage::cssModuleLoad('tidyAdmin/css/search_media.css', 'screen', $core->getVersion('tidyAdmin')) . "\n" .
                dcPage::jsModuleLoad('tidyAdmin/js/search_media.js', $core->getVersion('tidyAdmin')) . "\n";
        }

        // User defined CSS rules
        if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.css')) {
            echo dcPage::cssModuleLoad(dcPage::getVF('plugins/tidyAdmin/admin.css')) . "\n";
        }
        // User defined Javascript
        if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.js')) {
            echo dcPage::jsModuleLoad(dcPage::getVF('plugins/tidyAdmin/admin.js')) . "\n";
        }
    }

    public static function adminDashboardFavorites($core, $favs)
    {
        $favs->register('tidyAdmin', [
            'title'       => __('Tidy Administration'),
            'url'         => 'plugin.php?p=tidyAdmin',
            'small-icon'  => urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
            'large-icon'  => urldecode(dcPage::getPF('tidyAdmin/icon.svg')),
            'permissions' => $core->auth->isSuperAdmin(),
        ]);
    }
}
