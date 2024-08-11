<?php

namespace taobig\yii\gii\model;

use ReflectionClass;
use taobig\yii\model\BaseModel;
use taobig\yii\model\BaseQuery;
use Yii;
use yii\gii\CodeFile;
use yii\gii\Module;

class Generator extends \yii\gii\generators\model\Generator
{

    public $baseClass = BaseModel::class;
    public $generateLabelsFromComments = true;
    public $useTablePrefix = true;
    public $generateQuery = true;
    public $queryBaseClass = BaseQuery::class;
    public $optimisticLockFiled = 'version';
    public $optimisticLockFiledComment = 'optimistic lock filed';

    private function unStickyAttributes(): array
    {
        return ['baseClass', 'generateLabelsFromComments', 'useTablePrefix', 'generateQuery', 'queryBaseClass'];
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '自定义 Model  Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table.';
    }

    /**
     * {@inheritdoc}
     * @return string
     * @throws \ReflectionException
     */
    public function formView()
    {
        $class = new ReflectionClass(\yii\gii\generators\model\Generator::class);

        return dirname($class->getFileName()) . '/form.php';
    }


    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        $parentStickyAttributes = parent::stickyAttributes();
        foreach ($parentStickyAttributes as $key => $parentStickyAttribute) {
            if (in_array($parentStickyAttribute, $this->unStickyAttributes(), true)) {
                unset($parentStickyAttributes[$key]);
            }
        }
        return $parentStickyAttributes;
    }


    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $giiModule = new Module(uniqid());
        $reflector = new ReflectionClass(get_class($giiModule));
        $reflectionMethod = $reflector->getMethod("defaultVersion");
        if (PHP_VERSION_ID < 80100) {
            $reflectionMethod->setAccessible(true); // Note: As of PHP 8.1.0, calling this method has no effect; all methods are invokable by default.
        }
        $giiVersion = $reflectionMethod->invoke($giiModule);

        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model :
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableRelations = isset($relations[$tableName]) ? $relations[$tableName] : [];
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'properties' => $this->generateProperties($tableSchema),
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => $tableRelations,
                'relationsClassHints' => $this->generateRelationsClassHints($tableRelations, $this->generateQuery),
            ];
            $params['giiVersion'] = $giiVersion;
            $params['optimisticLockFiled'] = $this->optimisticLockFiled;
            $params['optimisticLockFiledComment'] = $this->optimisticLockFiledComment;
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );

            // query :
            if ($queryClassName) {
                $params['className'] = $queryClassName;
                $params['modelClassName'] = $modelClassName;
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->queryNs)) . '/' . $queryClassName . '.php',
                    $this->render('query.php', $params)
                );
            }
        }

        return $files;
    }

}
