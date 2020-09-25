<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%ticket}}`
 */
class m200921_110207_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'ticket_id' => $this->integer()->notNull(),
            'content' => $this->text(),
            'creation_date' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-comment-author_id}}',
            '{{%comment}}',
            'author_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-comment-author_id}}',
            '{{%comment}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `ticket_id`
        $this->createIndex(
            '{{%idx-comment-ticket_id}}',
            '{{%comment}}',
            'ticket_id'
        );

        // add foreign key for table `{{%ticket}}`
        $this->addForeignKey(
            '{{%fk-comment-ticket_id}}',
            '{{%comment}}',
            'ticket_id',
            '{{%ticket}}',
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
            '{{%fk-comment-author_id}}',
            '{{%comment}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-comment-author_id}}',
            '{{%comment}}'
        );

        // drops foreign key for table `{{%ticket}}`
        $this->dropForeignKey(
            '{{%fk-comment-ticket_id}}',
            '{{%comment}}'
        );

        // drops index for column `ticket_id`
        $this->dropIndex(
            '{{%idx-comment-ticket_id}}',
            '{{%comment}}'
        );

        $this->dropTable('{{%comment}}');
    }
}
