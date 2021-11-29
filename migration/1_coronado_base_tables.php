<?php
/**
 * Create Coronado base tables.
 *
 * Copyright 2021 B1 Systems GmbH (https://www.b1-systems.de)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author  Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @package  Coronado
 */
class CoronadoBaseTables extends Horde_Db_Migration_Base
{
    /**
     * Upgrade
     */
    public function up()
    {
        $t = $this->createTable('coronado_tickets', ['autoincrementKey' => 'ticket_id']);
        $t->column('ticket_code', 'string', ['limit' => 32, 'null' => false]);
        $t->column('ticket_date', 'int', ['limit' => 11, 'null' => false]);
        $t->column('ticket_owner', 'string', ['limit' => 255, 'null' => false, 'default' => '']);
        $t->end();

        $this->addIndex('coronado_tickets', array('ticket_owner'));
    }

    /**
     * Downgrade
     */
    public function down()
    {
        $this->dropTable('coronado_tickets');
    }
}
