<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $description
 * @property string|null $status
 * @property string $modification_date
 * @property int|null $admin_id
 * @property ViewComment[] $comments
 *
 * @property CommonUser $author
 */
class Ticket extends \yii\db\ActiveRecord
{
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_ACTIVE = 'ACTIVE';
    const NULL = 'NULL';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'title', 'description'], 'required'],
            [['author_id', 'admin_id'], 'integer'],
            [['description'], 'string'],
            [['modification_date'], 'safe'],
            [['title', 'status'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommonUser::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'modification_date' => 'Modification Date',
            'admin_id' => 'Admin ID',
        ];
    }

    public function getComments()
    {
        return $this->hasMany(ViewComment::class, ['ticket_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TicketQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TicketQuery(get_called_class());
    }
}
