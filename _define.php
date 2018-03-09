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

$this->registerModule(
    "Tidy Administration",                    // Name
    "Customize your dotclear administration", // Description
    "Franck Paul",                            // Author
    '0.8.1',                                  // Version
    array(
        'requires' => array(array('core', '2.14')),         // Dependencies
        'support'  => 'https://open-time.net/?q=tidyadmin', // Support URL
        'type'     => 'plugin'                             // Type
    )
);
