<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of tidyAdmin, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

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
