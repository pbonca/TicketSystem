<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200916_110448_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'status' => $this->string()->defaultValue('ACTIVE'),
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

        $this->dropTable('{{%ticket}}');
    }
}
