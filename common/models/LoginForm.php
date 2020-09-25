<?php
namespace common\models;

use DateTime;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $password;
    public $email;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password then updates the database
     * based on the time the user logged in.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $this->getUser()->last_seen = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
            $this->getUser()->save();

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return CommonUser|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = CommonUser::find()->ofEmail($this->email)->one();
        }
        return $this->_user;
    }

    public function adminLogin()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user->is_admin === 1) {
                $this->getUser()->last_seen = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');
                $this->getUser()->save();

                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        }
        return false;
    }
}
