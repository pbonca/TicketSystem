<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ViewComment]].
 *
 * @see ViewComment
 */
class ViewCommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ViewComment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    public function ofTicketId(int $id): ViewCommentQuery
    {
        return $this->andWhere(['ticket_id' => $id]);
    }

    /**
     * {@inheritdoc}
     * @return ViewComment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
