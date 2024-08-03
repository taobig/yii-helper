<?php

namespace taobig\yii\model;

use taobig\yii\exceptions\ActiveRecordSaveException;
use taobig\yii\exceptions\UserException;
use Yii;
use yii\db\ActiveRecord;

abstract class BaseModel extends ActiveRecord
{

//    public function optimisticLock()
//    {
//        return 'version';
//    }

    /**
     * @return string|null
     */
    public static function getSoftDeleteAttribute()
    {
        //return 'deleted_at'; //soft delete attribute
        return null; //no soft delete
    }

    public function getFirstErrorMessage(): string
    {
        if ($this->hasErrors()) {
            return current($this->getFirstErrors());
        }
        return '';
    }

    /**
     * @param array|null $attributeNames
     * @param bool $clearErrors
     * @return bool
     * @throws UserException
     */
    public function validateActiveRecord(array $attributeNames = null, bool $clearErrors = true): bool
    {
        $flag = parent::validate($attributeNames, $clearErrors);
        if (!$flag) {
            if ($this->hasErrors()) {
                throw new UserException($this->getFirstErrorMessage());
            }
        }

        return $flag;
    }

    /**
     * @throws ActiveRecordSaveException
     * @throws \Throwable
     */
    public function insertActiveRecord()
    {
        if (!$this->insert()) {
            if ($this->hasErrors()) {
                throw new ActiveRecordSaveException($this->getFirstErrorMessage());
            }
            throw new ActiveRecordSaveException(Yii::t('app', 'Insert record failed'));
        }
    }

    /**
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @return int
     * @throws ActiveRecordSaveException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateActiveRecord(bool $runValidation = true, array $attributeNames = null): int
    {
        $affectedNum = $this->update($runValidation, $attributeNames);
        if ($affectedNum === false) {
            if ($this->hasErrors()) {
                throw new ActiveRecordSaveException($this->getFirstErrorMessage());
            }
            throw new ActiveRecordSaveException(Yii::t('app', 'Update record failed'));
        }
        return (int)$affectedNum;
    }

    /**
     * @param bool $runValidation
     * @param array|null $attributeNames
     * @throws ActiveRecordSaveException
     */
    public function saveActiveRecord(bool $runValidation = true, array $attributeNames = null)
    {
        if (!$this->save($runValidation, $attributeNames)) {
            if ($this->hasErrors()) {
                throw new ActiveRecordSaveException($this->getFirstErrorMessage());
            }
            throw new ActiveRecordSaveException(Yii::t('app', 'Save record failed'));
        }
    }

}