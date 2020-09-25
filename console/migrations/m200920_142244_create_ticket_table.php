<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200920_142244_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'admin_id' => $this->integer(),
            'status' => $this->string()->defaultValue('ACTIVE'),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'modification_date' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-ticket-author_id}}',
            '{{%ticket}}',
            'author_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ticket-author_id}}',
            '{{%ticket}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `admin_id`
        $this->createIndex(
            '{{%idx-ticket-admin_id}}',
            '{{%ticket}}',
            'admin_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ticket-admin_id}}',
            '{{%ticket}}',
            'admin_id',
            '{{%user}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ticket-author_id}}',
            '{{%ticket}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-ticket-author_id}}',
            '{{%ticket}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ticket-admin_id}}',
            '{{%ticket}}'
        );

        // drops index for column `admin_id`
        $this->dropIndex(
            '{{%idx-ticket-admin_id}}',
            '{{%ticket}}'
        );

        $this->dropTable('{{%ticket}}');
    }
}
