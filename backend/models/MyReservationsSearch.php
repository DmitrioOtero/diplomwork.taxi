<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.05.2021
 * Time: 17:21
 */

namespace backend\models;


use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class MyReservationsSearch extends Reservation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trip_id', 'number_of_seats','rout_from_id','rout_to_id'], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'phone', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @param $trip_id
     * @return ActiveDataProvider
     */
    public function search($params, $trip_id)
    {
        $query = Reservation::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'trip_id' => $trip_id,
            'number_of_seats' => $this->number_of_seats,
            'rout_from_id' => $this->rout_from_id,
            'rout_to_id' => $this->rout_to_id,
        ]);

        $query
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
        ;

        return $dataProvider;
    }
}