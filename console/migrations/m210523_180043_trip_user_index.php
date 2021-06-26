<?php

use yii\db\Migration;

/**
 * Class m210523_180043_trip_user_index
 */
class m210523_180043_trip_user_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createIndex("user_id", "{{%trip}}", ["user_id"]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex("user_id", "{{%trip}}");
    }
}
