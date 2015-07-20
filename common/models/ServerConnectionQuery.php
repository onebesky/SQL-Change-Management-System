<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ServerConnection]].
 *
 * @see ServerConnection
 */
class ServerConnectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ServerConnection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ServerConnection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}