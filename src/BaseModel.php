<?php

namespace taobig\yii;

use taobig\yii\exceptions\ActiveRecordSaveException;
use taobig\yii\exceptions\UserException;
use yii\db\ActiveRecord;

abstract class BaseModel extends ActiveRecord
{

    /**
     * @return string|null
     */
    public static function getSoftDeleteAttribute()
    {
        return 'deleted_at';
    }

//    public function optimisticLock()
//    {
//        return 'version';
//    }

    /**
     * @param $condition
     * @return static|null ActiveRecord instance matching the condition, or `null` if nothing matches.
     * @throws \yii\base\InvalidConfigException
     */
    public static function findActiveOne($condition)
    {
        $query = static::findByCondition($condition);
        if (static::getSoftDeleteAttribute()) {
            $query->andWhere([static::getSoftDeleteAttribute() => 0]);
        }
        return $query->one();
    }

    /**
     * @param $condition
     * @return static[] an array of ActiveRecord instances, or an empty array if nothing matches.
     * @throws \yii\base\InvalidConfigException
     */
    public static function findActiveAll($condition)
    {
        $query = static::findByCondition($condition);
        if (static::getSoftDeleteAttribute()) {
            $query->andWhere([static::getSoftDeleteAttribute() => 0]);
        }
        return $query->all();
    }

    /**
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function softDelete()
    {
        $softDeleteAttribute = static::getSoftDeleteAttribute();
        if ($softDeleteAttribute) {
            $this->{$softDeleteAttribute} = time();
            return $this->update(true, [$softDeleteAttribute]);
        }
    }

    public function getFirstErrorMessage(): string
    {
        if ($this->hasErrors()) {
            return current($this->getFirstErrors());
        }
        return '';
    }


    public function validateActiveRecord($attributeNames = null, $clearErrors = true)
    {
        $flag = parent::validate($attributeNames, $clearErrors);
        if (!$flag) {
            if ($this->hasErrors()) {
                throw new UserException($this->getFirstErrorMessage());
            }
        }

        return $flag;
    }

    public function insertActiveRecord()
    {
        if (!$this->insert()) {
            if ($this->hasErrors()) {
                throw new ActiveRecordSaveException($this->getFirstErrorMessage());
            }
            throw new ActiveRecordSaveException('insert record failed');
        }
    }

    public function updateActiveRecord($runValidation = true, $attributeNames = null): int
    {
        $affectedNum = $this->update($runValidation, $attributeNames);
        if ($affectedNum === false) {
            if ($this->hasErrors()) {
                throw new ActiveRecordSaveException($this->getFirstErrorMessage());
            }
            throw new ActiveRecordSaveException('update record failed');
        }
        return (int)$affectedNum;
    }

    public function saveActiveRecord($runValidation = true, $attributeNames = null)
    {
        if (!$this->save($runValidation, $attributeNames)) {
            if ($this->hasErrors()) {
                throw new ActiveRecordSaveException($this->getFirstErrorMessage());
            }
            throw new ActiveRecordSaveException('save record failed');
        }
    }

}