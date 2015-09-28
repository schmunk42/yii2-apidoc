<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\apidoc\templates\bootstrap;

use yii\apidoc\models\TypeDoc;
use yii\helpers\StringHelper;

/**
 * Common methods for renderers
 */
trait RendererTrait
{
    /**
     * @var array official Yii extensions
     */
    public $extensions = [
        'dmstr_modules_pages',
        'schmunk42_giiant',
        'schmunk42_markdocs-module',
        'codemix_localeurls',
        'codemix_streamlog',
        'onebase_core',
        'ext_diemeisterei',
        'yii_gii',
        'app' // TODO: remove this workaround for app module detection
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
                $types = array_filter($types, function ($val) use ($navClasses) {
                    $class = str_replace('_','\\',$navClasses);
                    return strncmp($val->name, $class, strlen($class)) === 0;
                });
                break;
        }

        return $types;
    }
}
