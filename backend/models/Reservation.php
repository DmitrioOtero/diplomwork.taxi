<?php

namespace backend\models;

use common\models\User;
use yii;

/**
 * This is the model class for table "reservation".
 *
 * @property integer $id
 * @property integer $trip_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $phone
 * @property integer $number_of_seats
 * @property string $email
 * @property integer $rout_from_id
 * @property integer $rout_to_id
 *
 * @property Rout $routFrom
 * @property Rout $routTo
 * @property Trip $trip
 */
class Reservation extends yii\db\ActiveRecord
{
    public $agree_with_policy;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reservation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agree_with_policy', 'trip_id', 'first_name', 'middle_name', 'last_name', 'phone', 'number_of_seats', 'rout_from_id', 'rout_to_id'], 'required'],
            [['trip_id', 'number_of_seats','rout_from_id','rout_to_id'], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'phone', 'email'], 'string', 'max' => 255],

            ['agree_with_policy', 'match', 'pattern' => '/^1$/', 'message' => "Необходимо принять пользовательское соглашение"],

            ['phone', 'match', 'pattern' => '/^(8|7|\+7) \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trip_id' => 'Номер маршрута',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'phone' => 'Номер телефона',
            'number_of_seats' => 'Количество мест',
            'email' => 'Электронная почта',
            'rout_from_id' => 'Откуда',
            'rout_to_id' => 'Куда',
            'date_from' => 'Дата',
        ];
    }

    public function getRoutFrom()
    {
        return $this->hasOne(Rout::className(), ["id" => "rout_from_id"]);
    }

    public function getRoutTo()
    {
        return $this->hasOne(Rout::className(), ["id" => "rout_to_id"]);
    }

    public function getTrip()
    {
        return $this->hasOne(Trip::className(), ["id" => "trip_id"]);
    }
}
