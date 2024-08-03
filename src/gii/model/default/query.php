<?php
/**
 * This is the template for generating the ActiveQuery class.
 */

/* @var yii\web\View $this */
/* @var yii\gii\generators\model\Generator $generator */
/* @var string $tableName full table name */
/* @var string $className class name */
/* @var yii\db\TableSchema $tableSchema  */
/* @var string[] $labels list of attribute labels (name => label) */
/* @var string[] $rules list of validation rules */
/* @var array $relations list of relations (name => relation declaration) */
/* @var string $className class name */
/* @var string $modelClassName related model class name */
/* @var string $giiVersion */
/* @var array $properties the generated properties (property => type) */

$modelFullClassName = $modelClassName;
if ($generator->ns !== $generator->queryNs) {
    $modelFullClassName = '\\' . $generator->ns . '\\' . $modelFullClassName;
}

echo "<?php\n";
echo "//Auto-generated by Gii-{$giiVersion}\n";
?>

namespace <?= $generator->queryNs ?>;

/**
 * This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
 *
 * @see <?= $modelFullClassName . "\n" ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->queryBaseClass, '\\') . "\n" ?>
{

    /**
     * {@inheritdoc}
     * @return <?= $modelFullClassName ?>[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return <?= $modelFullClassName ?>|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
    * @param <?= $modelFullClassName ?> $model
    * @return $this
    */
    public function search($model)
    {
<?php foreach ($properties as $property => $data):
    if (class_exists('\\' . $generator->ns . '\\' . $modelFullClassName)) {
        if ($property === ('\\' . $generator->ns . '\\' . $modelFullClassName)::getSoftDeleteAttribute()) {
            continue;
        }
    } else {
        if ($property === ($generator->baseClass)::getSoftDeleteAttribute()) {
            continue;
        }
    }
    if($data['type'] === 'string'):?>
        $this->andFilterWhere(['like', '<?=$property?>', $model-><?=$property?>]);
<?php else:?>
        $this->andFilterWhere(['<?=$property?>' => $model-><?=$property?>]);
<?php endif;?>
<?php endforeach; ?>
        return $this;
    }
}