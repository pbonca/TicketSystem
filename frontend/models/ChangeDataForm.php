<?php


namespace frontend\models;

use common\models\CommonUser;
use Yii;


class ChangeDataForm extends \yii\base\Model
{
    public $name;
    public $currentPassword;
    public $newPassword;
    public $repeat;
    

    const SCENARIO_CHANGE_PASSWORD = "change_password";


    public function rules()
    {
        return[
            //sorrend fontos
            ['name', 'trim'],
            ['name', 'required'],
            [['currentPassword', 'repeat', 'newPassword'], 'string', 'min' => \Yii::$app->params['user.passwordMinLength']],
            [['currentPassword', 'newPassword', 'repeat'], 'required', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            ['currentPassword', 'validatePassword', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            ['newPassword', 'compare', 'compareAttribute' => 'repeat']
        ];
    }

 //   need to set the password to what i got from the form. Also need to set the name.

    public function fillFrom(CommonUser $user): ChangeDataForm
    {
        $this->name = $user->name;
        return $this;
    }

    public function fillTo(CommonUser $user): CommonUser
    {
        $user->name = $this->name;
        if (isset($this->newPassword)) {
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->newPassword);
        }

        return $user;
    }


    public function validatePassword($attribute, $params, $validator)
    {
        /** @var CommonUser $user */
        $user = Yii::$app->user->identity;

        return $user->validatePassword($attribute);
    }
}