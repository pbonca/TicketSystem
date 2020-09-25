<?php

namespace common\models;

use common\models\CommonUser;
use common\models\Ticket;
use common\models\TicketQuery;
use common\models\UserQuery;
use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $author_id
 * @property int $ticket_id
 * @property string|null $content
 * @property string $creation_date
 *
 * @property CommonUser $author
 * @property Ticket $ticket
 */
class ViewComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'ticket_id'], 'required'],
            [['author_id', 'ticket_id'], 'integer'],
            [['content'], 'string'],
            [['creation_date'], 'safe'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' =>CommonUser::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::className(), 'targetAttribute' => ['ticket_id' => 'id']],
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
            'ticket_id' => 'Ticket ID',
            'content' => 'Content',
            'creation_date' => 'Creation Date',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(CommonUser::className(), ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery|TicketQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticket_id']);
    }

    /**
     * {@inheritdoc}
     * @return ViewCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ViewCommentQuery(get_called_class());
    }
}
