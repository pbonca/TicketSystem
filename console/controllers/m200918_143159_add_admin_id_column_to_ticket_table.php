<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket}}`.
 */
class m200918_143159_add_admin_id_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ticket','admin_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ticket', 'admin_id');
    }
}
