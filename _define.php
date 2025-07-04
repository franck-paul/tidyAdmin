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
$this->registerModule(
    'Tidy Administration',
    'Customize your dotclear administration',
    'Franck Paul',
    '8.16.1',
    [
        'date'     => '2025-07-04T10:02:07+0200',
        'requires' => [['core', '2.31']],
        'type'     => 'plugin',
        'settings' => [],

        'details'    => 'https://open-time.net/?q=tidyadmin',
        'support'    => 'https://github.com/franck-paul/tidyadmin',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/tidyadmin/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
