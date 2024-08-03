<?php

namespace taobig\yii\model;

abstract class BaseQuery extends \yii\db\ActiveQuery
{

    /**
     * @param BaseModel $model
     * @return $this
     */
    abstract public function search($model);


    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @return $this
     */
    public final function active()
    {
        /** @var BaseModel $model */
        $model = (new $this->modelClass);
        if ($model->getSoftDeleteAttribute()) {
            $this->andWhere([$model->getSoftDeleteAttribute() => 0]);
        }
        return $this;
    }

}
