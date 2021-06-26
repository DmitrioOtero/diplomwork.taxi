<?php

use yii\db\Migration;

/**
 * Class m210523_135326_structure
 */
class m210523_135326_structure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up() //перевозчики
    {
        $this->addColumn('{{%user}}', 'organization', $this->string()->notNull()->defaultValue("")->comment('Название организации'));
        $this->addColumn('{{%user}}', 'phone', $this->string()->notNull()->defaultValue("")->comment('Номер телефона'));
        $this->addColumn('{{%user}}', 'address', $this->string()->notNull()->defaultValue("")->comment('Юр. адрес'));
        $this->addColumn('{{%user}}', 'inn', $this->string()->notNull()->defaultValue("")->comment('ИНН'));

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%trip}}', [//поездки
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('Id перевозчика'),
            'date' => $this->date()->notNull()->defaultValue("0000-00-00")->comment('Дата'),
            'time' => $this->time()->notNull()->defaultValue("00:00:00")->comment('Время'),
            'car_number' => $this->string()->notNull()->defaultValue("")->comment('Номер авто'),
            'car_model' => $this->string()->notNull()->defaultValue("")->comment('Модель авто'),
            'number_of_seats' => $this->integer()->notNull()->defaultValue(1)->comment('Коль-во мест'),
            'description' => $this->string()->notNull()->defaultValue("")->comment('Описание'),
        ], $tableOptions);
        $this->createIndex("car_number", "{{%trip}}", ["car_number"], true);

        $this->createTable('{{%rout}}', [//маршрут
            'id' => $this->primaryKey(),
            'trip_id' => $this->integer()->notNull()->comment('Id перевозчика'),
            'from' => $this->string()->notNull()->defaultValue("")->comment('Откуда'),
            'to' => $this->string()->notNull()->defaultValue("")->comment('Куда'),
            'sort' => $this->integer()->notNull()->defaultValue(0)->comment('Порядок сортировки'),
            'price' => $this->decimal(10,2)->notNull()->defaultValue(0)->comment('Цена'),
        ], $tableOptions);
        $this->createIndex("trip_id", "{{%rout}}", ["trip_id"]);
        $this->createIndex("trip_sort", "{{%rout}}", ["trip_id", "sort"], true);

        $this->createTable('{{%reservation}}', [//бронирование
            'id' => $this->primaryKey(),
            'trip_id' => $this->integer()->notNull()->comment('Id перевозчика'),
            'first_name' => $this->string()->notNull()->defaultValue("")->comment('Имя'),
            'middle_name' => $this->string()->notNull()->defaultValue("")->comment('Отчество'),
            'last_name' => $this->string()->notNull()->defaultValue("")->comment('Фамилия'),
            'phone' => $this->string()->notNull()->defaultValue("")->comment('Номер телефона'),
            'number_of_seats' => $this->integer()->notNull()->comment('Коль-во мест'),
            'email' => $this->string()->notNull()->comment('E-mail'),
            'rout_from_id' => $this->integer()->notNull()->defaultValue(0)->comment('Откуда'),
            'rout_to_id' => $this->integer()->notNull()->defaultValue(0)->comment('Куда'),
        ], $tableOptions);
        $this->createIndex("trip_id", "{{%reservation}}", ["trip_id"]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex("trip_id", "{{%reservation}}");
        $this->dropTable("{{%reservation}}");

        $this->dropIndex("trip_id", "{{%rout}}");
        $this->dropIndex("trip_sort", "{{%rout}}");
        $this->dropTable("{{%rout}}");

        $this->dropIndex("car_number", "{{%trip}}");
        $this->dropTable("{{%trip}}");
        
        $this->dropColumn('{{%user}}', 'inn');
        $this->dropColumn('{{%user}}', 'address');
        $this->dropColumn('{{%user}}', 'phone');
        $this->dropColumn('{{%user}}', 'organization');
    }
}
