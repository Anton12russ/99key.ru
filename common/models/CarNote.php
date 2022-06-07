<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "car_note".
 *
 * @property int $id
 * @property int $id_car
 * @property int $id_product
 * @property string $note
 */
class CarNote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_car', 'note'], 'required'],
            [['id_car', 'id_product'], 'integer'],
            [['note'], 'string', 'max' => 400],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_car' => 'Id Car',
            'note' => 'Note',
			'id_product' => 'id_product',
        ];
    }
}
