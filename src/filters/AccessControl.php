<?php

namespace taobig\yii\filters;

use taobig\yii\exceptions\ForbiddenHttpException;
use yii\web\User;

class AccessControl extends \yii\filters\AccessControl
{
    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User $user the current user
     * @throws ForbiddenHttpException
     */
    protected function denyAccess($user)
    {
        try {
            parent::denyAccess($user);
        } catch (\yii\web\ForbiddenHttpException $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }
    }
}
