<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "passanger_fields".
 *
 * @property int $id
 * @property int $views
 * @property string $text
 * @property string $marka
 * @property string $phone
 * @property string $coord_ot
 * @property string $coord_kuda
 */
class PassangerFields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'passanger_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'coord_ot', 'coord_kuda', 'passanger_id'], 'required'],
            [['views'], 'integer'],
			[['views'], 'default', 'value'=> '1'],
            [['text'], 'string'],
			[['passanger_id'], 'integer'],
            [['marka', 'coord_ot', 'coord_kuda', 'name'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'views' => 'Views',
            'text' => 'Text',
            'marka' => 'Marka',
            'phone' => 'Phone',
            'coord_ot' => 'Coord Ot',
            'coord_kuda' => 'Coord Kuda',
			'passanger_id' => 'passanger_id'
        ];
    }
}
