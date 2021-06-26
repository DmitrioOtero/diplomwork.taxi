<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $inn;
    public $address;
    public $phone;
    public $organization;
    public $agree_with_policy;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['agree_with_policy', 'username', 'inn', 'phone', 'organization'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже занято'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['agree_with_policy', 'match', 'pattern' => '/^1$/', 'message' => "Необходимо принять пользовательское соглашение"],

            [['inn', 'organization', 'address'], 'string', 'max' => 255],
            ['phone', 'match', 'pattern' => '/^(8|7|\+7) \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот e-mail уже занят'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Электронная почта',
            'inn' => 'ИНН',
            'address' => 'Адрес',
            'phone' => 'Номер телефона',
            'organization' => 'Название организации',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->inn = $this->inn;
        $user->address = $this->address;
        $user->phone = $this->phone;
        $user->organization = $this->organization;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save() && $this->sendEmail($user);

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
