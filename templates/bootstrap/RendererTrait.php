<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\apidoc\templates\bootstrap;

use yii\apidoc\models\TypeDoc;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

/**
 * Common methods for renderers
 */
trait RendererTrait
{
    /**
     * @var array official Yii extensions
     */
    public $extensions = [
        '@vendor/dmstr/yii2-bootstrap',
        '@vendor/schmunk42/yii2-giiant',
        '@vendor/schmunk42/yii2-markdocs-module',
        '@app/extensions/diemeisterei',
        '@vendor/codemix/yii2-localeurls',
        '@vendor/codemix/yii2-streamlog',
        '@vendor/dmstr/yii2-pages-module',
        '@vendor/dmstr/yii2-widgets-module',
        '@vendor/dmstr/yii2-helpers',
        '@vendor/dmstr/yii2-db',
        '@vendor/dmstr/yii2-migrate-command',
        '@vendor/dmstr/yii2-yaml-converter-command',
        '@vendor/rmrevin/yii2-fontawesome'
    ];

    /**
     * Returns nav TypeDocs
     * @param TypeDoc $type typedoc to take category from
     * @param TypeDoc[] $types TypeDocs to filter
     * @return array
     */
    public function getNavTypes($type, $types)
    {
        if ($type === null) {
            return $types;
        }

        return $this->filterTypes($types, $this->getTypeCategory($type));
    }

    /**
     * Returns category of TypeDoc
     * @param TypeDoc $type
     * @return string
     */
    protected function getTypeCategory($type)
    {
        $navClasses = 'app';
        if (isset($type)) {
            $parts = explode('\\',$type->name);
            if ($parts[0] == 'app') {
                $navClasses = $parts[0];
            } else {
                $navClasses = $parts[0].'\\'.$parts[1];
            }
        }
        return $navClasses;
    }

    /**
     * Returns types of a given class
     *
     * @param TypeDoc[] $types
     * @param string $navClasses
     * @return array
     */
    protected function filterTypes($types, $navClasses)
    {
        switch ($navClasses) {
            case 'app':
                $types = array_filter($types, function ($val) {
                    return strncmp($val->name, 'app\\', 4) === 0;
                });
                break;
            default:
                $types = array_filter($types, function ($type) use ($navClasses) {
                    \Yii::setAlias('@ext','/app/src/extensions');
                    \Yii::setAlias('@vendor','/app/vendor');
                    $path = str_replace('/app/','', realpath(\Yii::getAlias($navClasses)));
                    return strncmp($type->sourceFile, $path, strlen($path)) === 0;
                });
                break;
        }
        return $types;
    }
}
