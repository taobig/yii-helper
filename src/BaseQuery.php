<?php

namespace taobig\yii;

/**
 * @deprecated
 */
abstract class BaseQuery extends \taobig\yii\model\BaseQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @deprecated
     * @param BaseModel $model
     * @return $this
     */
    abstract public function search($model);

    /**
     * @deprecated
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

    /**
     * @deprecated
     * @param BaseModel $model
     * @return $this
     */
    public final function searchActive(BaseModel $model)
    {
        if ($model->getSoftDeleteAttribute()) {
            $this->andWhere([$model->getSoftDeleteAttribute() => 0]);
        }
        $this->search($model);
        return $this;
    }

}
