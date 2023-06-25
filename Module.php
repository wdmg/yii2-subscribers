<?php

namespace wdmg\subscribers;

/**
 * Yii2 Subscribers
 *
 * @category        Module
 * @version         2.0.0
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-subscribers
 * @copyright       Copyright (c) 2019 - 2023 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use wdmg\helpers\ArrayHelper;
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
    private $version = "2.0.0";

    /**
     * @var string, route to web for manage subscription
     */
    public $webRoute = "/subscribe";

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

        // Normalize route for web
        $this->webRoute = self::normalizeRoute($this->webRoute);

    }

    /**
     * {@inheritdoc}
     */
    public function dashboardNavItems($options = null)
    {
        $items = [
            'label' => $this->name,
            'url' => '#',
            'icon' => 'fa fa-fw fa-address-card',
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

	    if (!is_null($options)) {

		    if (isset($options['count'])) {
			    $items['label'] .= '<span class="badge badge-default float-right">' . $options['count'] . '</span>';
			    unset($options['count']);
		    }

		    if (is_array($options))
			    $items = ArrayHelper::merge($items, $options);

	    }

	    return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);

        if (isset(Yii::$app->params["subscribers.webRoute"]))
            $this->webRoute = Yii::$app->params["subscribers.webRoute"];

    }
}