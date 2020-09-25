<?php


namespace backend\models;

use phpDocumentor\Reflection\Types\This;
use yii\base\Model;
use common\models\CommonUser;
use Yii;


class ChangeUserData extends Model
{
    public $name;
    public $isAdmin;
    public $password;

    const SCENARIO_CHANGE_PASSWORD = "change_password";


    public function rules()
    {
        return[
            //sorrend fontos
            ['name', 'trim'],
            ['name', 'required'],
            ['password', 'string', 'min' => \Yii::$app->params['user.passwordMinLength']],
            ['password', 'required', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            ['password', 'validatePassword', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            ['isAdmin', 'required'],
        ];
    }

    public function fillFrom(CommonUser $user): ChangeUserData
    {
        $this->name = $user->name;
        $this->isAdmin = $user->is_admin;

        return $this;
    }

    public function fillTo(CommonUser $user): CommonUser
    {
        $user->name = $this->name;
        if ($this->password != null) {
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        $user->is_admin = $this->isAdmin;

        return $user;
    }


    public function validatePassword($attribute, $params, $validator)
    {
        /** @var CommonUser $user */
        $user = Yii::$app->user->identity;

        return $user->validatePassword($attribute);
    }
}