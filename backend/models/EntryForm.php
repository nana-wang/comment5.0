<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class EntryForm extends Model
{
    public $text;
    public $face;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
}
