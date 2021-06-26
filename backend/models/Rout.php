<?php

namespace backend\models;

use common\models\User;
use yii;

/**
 * This is the model class for table "rout".
 *
 * @property integer $id
 * @property integer $trip_id
 * @property string $from
 * @property string $to
 * @property integer $sort
 * @property string $price
 * @property integer $number_of_seats
 *
 * @property Trip $trip
 */
class Rout extends yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trip_id', 'from', 'to', 'sort', 'price', 'number_of_seats'], 'required'],
            [['trip_id', 'sort', 'number_of_seats'], 'integer'],
            [['from', 'to', 'price'], 'string', 'max' => 255],
            [['price'], 'safe'],

            ['price', 'match', 'pattern' => '/^\d+(.\d{1,2})?$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trip_id' => 'Номер поездки',
            'from' => 'Откуда',
            'to' => 'Куда',
            'sort' => 'Номер по порядку',
            'price' => 'Стоимость',
            'number_of_seats' => 'Количество мест',
        ];
    }

    public function getTrip()
    {
        return $this->hasOne(Trip::className(), ["id" => "trip_id"]);
    }
}
