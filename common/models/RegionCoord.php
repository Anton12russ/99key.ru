<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "region_coord".
 *
 * @property int $id
 * @property int $region_id
 * @property string $coordlat
 * @property string $coordlon
 */
class RegionCoord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region_coord';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id', 'coordlat', 'coordlon'], 'required'],
            [['region_id'], 'integer'],
            [['coordlat', 'coordlon'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_id' => 'Region ID',
            'coordlat' => 'Coordlat',
            'coordlon' => 'Coordlon',
        ];
    }
}
