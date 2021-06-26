<?php

namespace backend\models;

use common\models\User;
use yii;

/**
 * This is the model class for table "trip".
 *
 * @property integer $id
 * @property string $date
 * @property integer $user_id
 * @property string $time
 * @property string $car_number
 * @property string $car_model
 * @property string $description
 *
 * @property User $user
 * @property array $rout
 */
class Trip extends yii\db\ActiveRecord
{
    private $routs;
    private $user;
    public $from_id = null;
    private $departure = null;
    public $to_id = null;
    private $destination = null;
    private $number_of_seats = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'time', 'car_number', 'car_model'], 'required'],
            [['user_id'], 'integer'],
            [['date', 'time', 'car_number', 'car_model', 'description'], 'string', 'max' => 255],

            ['date', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
            ['time', 'match', 'pattern' => '/^\d{2}:\d{2}:\d{2}$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'user_id' => 'Номер пользователя',
            'time' => 'Время',
            'car_number' => 'Номер автомобиля',
            'car_model' => 'Марка автомобиля',
            'description' => 'Описание',
        ];
    }

    public function getUser()
    {
        if (!isset($this->user)) {
            $this->user = $this->hasOne(User::className(), ["id" => "user_id"]);
        }

        return $this->user;
    }

    public function getRout()
    {
        if (!isset($this->routs)) {
            $this->routs = $this->hasMany(Rout::className(), ["trip_id" => "id"]);
        }

        return $this->routs;
    }

    public function getPrice($from, $to) {
        $routs = $this->rout;
        $price = 0;
        $need_add_price = false;
        foreach ($routs as $rout) {
            if (($need_add_price) or (mb_stristr($rout->from, mb_strtolower($from)) !== false)) {
                $need_add_price = true;
                $price += $rout->price;
            }
            if (($need_add_price) and (mb_stristr($rout->to, mb_strtolower($to)) !== false)) {
                break;
            }
        }

        return $price;
    }

    public function getDeparture($from) {
        if (is_null($this->departure)) {
            $routs = $this->rout;
            foreach ($routs as $rout) {
                if (mb_stristr($rout->from, mb_strtolower($from)) !== false) {
                    $this->from_id = $rout->id;
                    return $rout->from;
                }
            }
        }

        return $this->departure;
    }

    public function getDestination($to) {
        if (is_null($this->destination)) {
            $routs = $this->rout;
            foreach ($routs as $rout) {
                if (mb_stristr($rout->to, mb_strtolower($to)) !== false) {
                    $this->to_id = $rout->id;
                    return $rout->to;
                }
            }
        }

        return $this->destination;
    }

    public function getNumberOfSeats($from, $to) {
        if ($this->number_of_seats === 0) {
            $routs = $this->rout;
            $count = PHP_INT_MAX;
            $rout_start_id = 0;
            foreach ($routs as $rout) {
                echo "<script>console.log('".$rout->id."');</script>";
                if ((mb_stristr($rout->from, mb_strtolower($from)) !== false) or ($rout_start_id != 0)) {
                    echo "<script>console.log('В пути');</script>";
                    if ($rout->number_of_seats < $count) {
                        echo "<script>console.log('Берём ".$rout->number_of_seats."');</script>";
                        $count = $rout->number_of_seats;
                        if ($rout_start_id === 0) {
                            $rout_start_id = $rout->id;
                        }
                    }
                }
                if (mb_stristr($rout->to, mb_strtolower($to)) !== false) {
                    $reserved_seats = Reservation::find()
                        ->where(["trip_id" => $this->id])
                        ->andWhere([
                            "OR",
                            [
                                "AND",
                                [">=", "rout_from_id", $rout_start_id],
                                ["<=", "rout_from_id", $rout->id],
                            ],
                            [
                                "AND",
                                [">=", "rout_to_id", $rout_start_id],
                                ["<=", "rout_to_id", $rout->id],
                            ],
                        ])
                        ->sum("number_of_seats");
                    echo "<script>console.log('Зарезервировано мест: ".$reserved_seats."');</script>";
                    $this->number_of_seats = $count - $reserved_seats;
                    break;
                }
            }
        }

        return $this->number_of_seats;
    }
}
