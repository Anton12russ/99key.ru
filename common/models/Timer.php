<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "timer".
 *
 * @property int $id
 * @property string $cod
 * @property string $id_block
 * @property string $tyme
 */
class Timer extends \yii\db\ActiveRecord
{
	public $href;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cod', 'id_block', 'tyme'], 'required'],
            [['cod'], 'string'],
            [['tyme'], 'safe'],
            [['id_block'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cod' => 'Код',
            'id_block' => 'div ID блока',
			'href' => 'ссылка перенаправления',
            'tyme' => 'Время создания',
        ];
    }
}
