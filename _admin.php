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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

// dead but useful code, in order to have translations
__('Tidy Administration') . __('Customize your dotclear administration');

$_menu['System']->addItem(
    __('Tidy Administration'),
    'plugin.php?p=tidyAdmin',
    urldecode(dcPage::getPF('tidyAdmin/icon.png')),
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

        // User defined CSS rules
        if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.css')) {
            echo
            dcPage::cssLoad(urldecode(dcPage::getVF('plugins/tidyAdmin/admin.css'))) . "\n";
        }
        // User defined Javascript
        if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.js')) {
            echo
            dcPage::jsLoad(urldecode(dcPage::getVF('plugins/tidyAdmin/admin.js'))) . "\n";
        }
    }

    public static function adminDashboardFavorites($core, $favs)
    {
        $favs->register('tidyAdmin', [
            'title'       => __('Tidy Administration'),
            'url'         => 'plugin.php?p=tidyAdmin',
            'small-icon'  => urldecode(dcPage::getPF('tidyAdmin/icon.png')),
            'large-icon'  => urldecode(dcPage::getPF('tidyAdmin/icon-big.png')),
            'permissions' => $core->auth->isSuperAdmin()
        ]);
    }
}
