<?php

namespace common\models;
use common\models\Field;
use Yii;

/**
 * This is the model class for table "blog_field".
 *
 * @property int $id
 * @property int $dop
 * @property int $message
 * @property int $field
 * @property string $value
 */
class BlogField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'value'], 'required'],
            [['message', 'field'], 'integer'],
            [['value', 'dop'], 'string'],
        ];
    }
 
 
   //Связь с field
	public function getFields() {
    return $this->hasOne(Field::className(),['id'=>'field']);
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'field' => 'Field',
            'value' => 'Value',
			'dop' => 'dop',
        ];
    }
}
