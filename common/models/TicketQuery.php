<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Ticket]].
 *
 * @see Ticket
 */
class TicketQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Ticket[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param int $id the id of the ticket
     * @return TicketQuery returns the ticket with the id of $id
     */
    public function ofId(int $id) : TicketQuery
    {
        return $this->andWhere(['id' => $id]);
    }

    public function ofuserId(int $id): TicketQuery
    {
        return $this->andWhere(['author_id' => $id]);
    }

    public function ofAdminId(int $id): TicketQuery
    {
        return $this->andWhere(['admin_id' => $id]);
    }

    /**
     * {@inheritdoc}
     * @return Ticket|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
