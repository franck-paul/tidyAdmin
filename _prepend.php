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

// User defined Locales
if (file_exists(path::real(DC_VAR) . '/plugins/tidyAdmin/admin.po')) {
    l10n::set(path::real(DC_VAR) . '/plugins/tidyAdmin/admin');
}
