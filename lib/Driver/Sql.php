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
 * Coronado storage implementation for the Horde_Db database abstraction layer.
 *
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2007-2017 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Coronado
 */
class Coronado_Driver_Sql extends Coronado_Driver
{
    /**
     * Handle for the current database connection.
     *
     * @var Horde_Db_Adapter
     */
    protected $_db;

    /**
     * Storage variable.
     *
     * @var array
     */
    protected $_foo = array();

    /**
     * Constructs a new SQL storage object.
     *
     * @param array $params  Class parameters:
     *                       - db:    (Horde_Db_Adapater) A database handle.
     *                       - table: (string, optional) The name of the
     *                                database table.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $params = array())
    {
        if (!isset($params['db'])) {
            throw new InvalidArgumentException('Missing db parameter.');
        }
        $this->_db = $params['db'];
        unset($params['db']);

        parent::__construct($params);
    }

    /**
     * Retrieves the foos from the database.
     *
     * @throws Coronado_Exception
     */
    public function retrieve()
    {
        /* Build the SQL query. */

        // Unrestricted query

        $query = 'SELECT * FROM coronado_items';

        // Restricted query alternative

        //$query = 'SELECT * FROM coronado_items WHERE foo = ?';
        //$values = array($this->_params['bar']);

        /* Execute the query. */
        try {
            $rows = $this->_db->selectAll($query, $values);
        } catch (Horde_Db_Exception $e) {
            throw new Coronado_Exception($e);
        }

        /* Store the retrieved values in the foo variable. */
        $this->_foo = array_merge($this->_foo, $rows);
    }

    /**
     * Stores a foo in the database.
     *
     * @throws Sms_Exception
     */
    public function store($data)
    {
        $query = 'INSERT INTO coronado_items' .
                 ' (item_owner, item_data)' .
                     ' VALUES (?, ?)';
        $values = array($GLOBALS['registry']->getAuth(),
                        $data);

        try {
            $this->_db->insert($query, $values);
        } catch (Horde_Db_Exception $e) {
            throw new Sms_Exception($e->getMessage());
        }
    }
}
