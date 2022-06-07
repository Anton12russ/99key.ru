<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cron".
 *
 * @property int $id
 * @property int $time
 * @property string $name
 */
class Cron extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'name'], 'required'],
            [['time'], 'integer'],
            [['name'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'name' => 'Name',
        ];
    }
}
