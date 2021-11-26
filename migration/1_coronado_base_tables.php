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
        $t = $this->createTable('coronado_items', array('autoincrementKey' => 'item_id'));
        $t->column('item_owner', 'string', array('limit' => 255, 'null' => false));
        $t->column('item_data', 'string', array('limit' => 64, 'null' => false));
        $t->end();

        $this->addIndex('coronado_items', array('item_owner'));
    }

    /**
     * Downgrade
     */
    public function down()
    {
        $this->dropTable('coronado_items');
    }
}
