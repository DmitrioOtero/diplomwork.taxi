<?php

use yii\db\Migration;

/**
 * Class m210528_162227_move_number_of_seats_from_trip_to_rout
 */
class m210528_162227_move_number_of_seats_from_trip_to_rout extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn("{{%trip}}", "number_of_seats");
        $this->addColumn("{{%rout}}", "number_of_seats", $this->integer()->notNull()->comment('Коль-во мест'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%rout}}", "number_of_seats");
        $this->addColumn("{{%trip}}", "number_of_seats", $this->integer()->notNull()->comment('Коль-во мест'));
    }
}
