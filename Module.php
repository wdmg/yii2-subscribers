<?php

namespace wdmg\subscribers;

/**
 * Yii2 Subscribers
 *
 * @category        Module
 * @version         1.0.0
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-subscribers
 * @copyright       Copyright (c) 2019 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use wdmg\base\BaseModule;

/**
 * Subscribers module definition class
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wdmg\subscribers\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = "all/index";

    /**
     * @var string, the name of module
     */
    public $name = "Subscribers";

    /**
     * @var string, the description of module
     */
    public $description = "Subscribers manager";

    /**
     * @var string the module version
     */
    private $version = "1.0.0";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 9;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Set version of current module
        $this->setVersion($this->version);

        // Set priority of current module
        $this->setPriority($this->priority);

    }

    /**
     * {@inheritdoc}
     */
    public function dashboardNavItems($createLink = false)
    {
        $items = [
            'label' => $this->name,
            'url' => '#',
            'icon' => 'fa-address-card',
            'active' => in_array(\Yii::$app->controller->module->id, [$this->id]),
            'items' => [
                [
                    'label' => Yii::t('app/modules/subscribers', 'All subscribers'),
                    'url' => [$this->routePrefix . '/subscribers/all/'],
                    'active' => (in_array(\Yii::$app->controller->module->id, ['subscribers']) &&  Yii::$app->controller->id == 'all'),
                ],
                [
                    'label' => Yii::t('app/modules/subscribers', 'Subscribers list'),
                    'url' => [$this->routePrefix . '/subscribers/list/'],
                    'active' => (in_array(\Yii::$app->controller->module->id, ['subscribers']) &&  Yii::$app->controller->id == 'list'),
                ],
            ]
        ];
        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
    }
}