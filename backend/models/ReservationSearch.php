<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.05.2021
 * Time: 18:53
 */

namespace backend\models;


use common\models\User;
use yii;
use yii\data\ActiveDataProvider;

class ReservationSearch extends Trip
{
    public  $date_from;
    public  $date_to;
    public  $from;
    public  $to;
    public  $price;
    public  $organization;
    public  $number_of_seats;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'date_from', 'date_to', 'number_of_seats'], 'required'],
            [['user_id', 'number_of_seats'], 'integer'],
            [['date', 'time', 'car_number', 'car_model', 'description', 'from', 'to', 'price', 'organization'], 'string', 'max' => 255],
            ['date', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
            ['time', 'match', 'pattern' => '/^\d{2}:\d{2}:\d{2}$/'],
            ['number_of_seats', 'compare', 'compareValue' => 1, 'operator' => '>='],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from' => 'Откуда',
            'to' => 'Куда',
            'date' => 'Дата поездки',
            'number_of_seats' => 'Количество мест',
            'organization' => 'Наименование организации',
            'date_from' => 'Дата',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Trip::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        if (!$this->validate() or count($params) == 0) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'date' => $this->date,
        ]);
        $query->andFilterWhere([">=", "date", $this->date_from]);
        $query->andFilterWhere(["<=", "date", $this->date_to]);

        if (!empty($this->organization)) {
            $users = User::find()
                ->distinct()
                ->select("id")
                ->where(["like", "organization", $this->organization])
                ->column();

            $query->andWhere([
                'user_id' => $users,
            ]);
        }

        $routs_from = Rout::find()
            ->filterWhere(["LIKE", "from", $this->from])
            ->indexBy("trip_id")
            ->asArray()
            ->all();
//        echo "<script>console.log(\"".print_r($routs_from, true)."\");</script>";
        $routs_to = Rout::find()
            ->filterWhere(["LIKE", "to", $this->to])
            ->indexBy("trip_id")
            ->asArray()
            ->all();
//        echo "<script>console.log(\"".print_r($routs_to, true)."\");</script>";
//        if ((count($routs_from) > 0) and (count($routs_to) > 0) and ($routs_from[0]["id"] > $routs_to[0]["id"])) {//Убираем поиск из точки А в точку А
//            $query->where('0=1');
//            return $dataProvider;
//        }
        $routs = array_intersect(array_column($routs_from, "trip_id"), array_column($routs_to, "trip_id"));
        $query->andWhere(["id" => $routs]);

        $trip_id_with_enough_seats = [];
        foreach ($routs as $trip_id) {
            $trip = Trip::findOne($trip_id);
            if ($trip->getNumberOfSeats($routs_from[$trip_id]["from"], $routs_to[$trip_id]["to"]) >= $this->number_of_seats) {
                $trip_id_with_enough_seats[] = $trip_id;
            }
        }
        $query->andWhere(["id" => $trip_id_with_enough_seats]);


        return $dataProvider;
    }
}