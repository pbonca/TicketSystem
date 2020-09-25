<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CommonUser]].
 *
 * @see CommonUser
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CommonUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    public function ofEmail(string $email): UserQuery
    {
        return $this->andWhere(['email' => $email]);
    }

    public function ofId(int $id): UserQuery
    {
        return $this->andWhere(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     * @return CommonUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
