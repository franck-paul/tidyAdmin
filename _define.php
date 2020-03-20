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

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "Tidy Administration",                    // Name
    "Customize your dotclear administration", // Description
    "Franck Paul",                            // Author
    '0.10',                                   // Version
    [
        'requires' => [['core', '2.16']],                         // Dependencies
        'support'  => 'https://github.com/franck-paul/tidyadmin', // Support URL
        'details'  => 'https://open-time.net/?q=tidyadmin',       // Details URL
        'settings' => [],                                         // Settings
        'type'     => 'plugin'                                   // Type
    ]
);
