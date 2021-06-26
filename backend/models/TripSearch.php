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

class TripSearch extends Trip
{
    public  $routs;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['date', 'time', 'car_number', 'car_model', 'description', 'routs'], 'string', 'max' => 255],
            ['date', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/'],
            ['time', 'match', 'pattern' => '/^\d{2}:\d{2}:\d{2}$/'],
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

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => Yii::$app->user->id,
            'date' => $this->date,
            'time' => $this->time,
        ]);

        if (!empty($this->routs)) {
            $routs = Rout::find()
                ->distinct()
                ->select("trip_id")
                ->filterWhere([
                    "OR",
                    ["LIKE", "from", $this->routs],
                    ["LIKE", "from", $this->routs],
                ])->column();
            $query->andFilterWhere(["id" => $routs]);
        }

        $query
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'car_number', $this->car_number])
        ;

        return $dataProvider;
    }
}