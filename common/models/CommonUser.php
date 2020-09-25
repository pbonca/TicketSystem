<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\StaleObjectException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $auth_key
 * @property string $registration_date
 * @property int|null $is_admin
 * @property string $last_seen
 * @property string|null $verification_token
 */
class CommonUser extends \yii\db\ActiveRecord implements IdentityInterface
{


    public static function tableName()
    {
        return 'user';
    }


    public function rules()
    {
        return [
            [['email', 'name', 'password_hash', 'auth_key'], 'required'],
            [['registration_date', 'last_seen'], 'safe'],
            [['is_admin'], 'integer'],
            [['email', 'name', 'password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'auth_key' => 'Auth Key',
            'registration_date' => 'Registration Date',
            'is_admin' => 'Is Admin',
            'last_seen' => 'Last Seen',
            'verification_token' => 'Verification Token',
        ];
    }

    /**
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function findIdentity($id): CommonUser
    {
        return self::find()->ofId($id)->one();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) : bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) : bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validateIsAdmin($id)
    {
        $user = self::find()->ofId($id)->one();

        return ($user->is_admin === 1);
    }

    public function deleteUser($user)
    {
        try {
            $user->delete();
        } catch (StaleObjectException $e) {
            die($e);
        }
    }

    public static function getUsers()
    {
        return self::find()->all();
    }

    public static function getName(int $id) : string
    {
        $user = self::find()->andWhere(['id' => $id])->one();
        if ($user === null) {
            return 'The user is non-existent';
        }
        return $user->name;
    }

    public function printMyData()
    {
        return ["Name" => $this->name,
            "Email" => $this->email,
            "Registration date" => $this->registration_date,
            "Last seen" => $this->last_seen
        ];
    }
}
