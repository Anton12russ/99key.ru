<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "region_case".
 *
 * @property int $id
 * @property int $id_region
 * @property string $name
 */
class RegionCase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region_case';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_region', 'name'], 'required'],
            [['id_region'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_region' => 'Id Region',
            'name' => 'Name',
        ];
    }
}
