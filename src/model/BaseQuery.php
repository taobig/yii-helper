<?php

namespace taobig\yii\model;

abstract class BaseQuery extends \yii\db\ActiveQuery
{

    /**
     * @param BaseModel $model
     * @return $this
     */
    abstract public function search($model): static;


    /*public function active(): static
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public final function active(): static
    {
        /** @var BaseModel $model */
        $model = (new $this->modelClass);
        if ($model->getSoftDeleteAttribute()) {
            $this->andWhere([$model->getSoftDeleteAttribute() => 0]);
        }
        return $this;
    }

}
