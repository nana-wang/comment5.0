<?php

namespace frontend\models;

/**
 * This is the ActiveQuery class for [[DwSensitive]].
 *
 * @see DwSensitive
 */
class UsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DwSensitive[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DwSensitive|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}