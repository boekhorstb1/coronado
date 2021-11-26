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
 * Coronado_Driver factory.
 *
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2010-2017 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Coronado
 */
class Coronado_Factory_Driver extends Horde_Core_Factory_Injector
{
    /**
     * @var array
     */
    private $_instances = array();

    /**
     * Return an Coronado_Driver instance.
     *
     * @return Coronado_Driver
     */
    public function create(Horde_Injector $injector)
    {
        $driver = Horde_String::ucfirst($GLOBALS['conf']['storage']['driver']);
        $signature = serialize(array($driver, $GLOBALS['conf']['storage']['params']['driverconfig']));
        if (empty($this->_instances[$signature])) {
            switch ($driver) {
            case 'Sql':
                try {
                    if ($GLOBALS['conf']['storage']['params']['driverconfig'] == 'horde') {
                        $db = $injector->getInstance('Horde_Db_Adapter');
                    } else {
                        $db = $injector->getInstance('Horde_Core_Factory_Db')
                            ->create('coronado', 'storage');
                    }
                } catch (Horde_Exception $e) {
                    throw new Coronado_Exception($e);
                }
                $params = array('db' => $db);
                break;
            case 'Ldap':
                try {
                    $params = array('ldap' => $injector->getIntance('Horde_Core_Factory_Ldap')->create('coronado', 'storage'));
                } catch (Horde_Exception $e) {
                    throw new Coronado_Exception($e);
                }
                break;
            }
            $class = 'Coronado_Driver_' . $driver;
            $this->_instances[$signature] = new $class($params);
        }

        return $this->_instances[$signature];
    }
}
