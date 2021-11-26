<?php
/**
 * Copyright 2021 B1 Systems GmbH (https://www.b1-systems.de)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Coronado
 */

/**
 * Coronado AJAX application API.
 *
 * This file defines the AJAX actions provided by this module. The primary
 * AJAX endpoint is represented by horde/services/ajax.php but that handler
 * will call the module specific actions defined in this class.
 *
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2012-2017 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Coronado
 */
class Coronado_Ajax_Application extends Horde_Core_Ajax_Application
{
    /**
     * Application specific initialization tasks should be done in here.
     */
    protected function _init()
    {
        // This adds the 'noop' action to the current application.
        $this->addHandler('Horde_Core_Ajax_Application_Handler_Noop');
    }

}
