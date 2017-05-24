<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[DwSensitiveLevel]].
 *
 * @see DwSensitiveLevel
 */
class DwSensitiveLevelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DwSensitiveLevel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DwSensitiveLevel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
