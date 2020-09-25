<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m180909_123451_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if($this->db->driverName === 'mysql'){
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'password_hash' =>$this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'registration_date' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'is_admin' => $this->boolean()->defaultValue(false),
            'last_seen' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
