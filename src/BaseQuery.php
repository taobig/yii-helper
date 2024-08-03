<?php

namespace taobig\yii;

/**
 * @deprecated
 */
abstract class BaseQuery extends \taobig\yii\model\BaseQuery
{

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
