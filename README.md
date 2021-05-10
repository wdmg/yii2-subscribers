[![Yii2](https://img.shields.io/badge/required-Yii2_v2.0.40-blue.svg)](https://packagist.org/packages/yiisoft/yii2)
[![Downloads](https://img.shields.io/packagist/dt/wdmg/yii2-subscribers.svg)](https://packagist.org/packages/wdmg/yii2-subscribers)
[![Packagist Version](https://img.shields.io/packagist/v/wdmg/yii2-subscribers.svg)](https://packagist.org/packages/wdmg/yii2-subscribers)
![Progress](https://img.shields.io/badge/progress-ready_to_use-green.svg)
[![GitHub license](https://img.shields.io/github/license/wdmg/yii2-subscribers.svg)](https://github.com/wdmg/yii2-subscribers/blob/master/LICENSE)

<img src="./docs/images/yii2-subscribers.png" width="100%" alt="Yii2 Subscribers" />

# Yii2 Subscribers
Subscribers manager for Yii2.

This module is an integral part of the [Butterfly.СMS](https://butterflycms.com/) content management system, but can also be used as an standalone extension.

Copyrights (c) 2019-2021 [W.D.M.Group, Ukraine](https://wdmg.com.ua/)

# Requirements 
* PHP 5.6 or higher
* Yii2 v.2.0.40 and newest
* [Yii2 Base](https://github.com/wdmg/yii2-base) module (required)
* [Yii2 SelectInput](https://github.com/wdmg/yii2-selectinput) widget

# Installation
To install the module, run the following command in the console:

`$ composer require "wdmg/yii2-subscribers"`

After configure db connection, run the following command in the console:

`$ php yii subscribers/init`

And select the operation you want to perform:
  1) Apply all module migrations
  2) Revert all module migrations

# Migrations
In any case, you can execute the migration and create the initial data, run the following command in the console:

`$ php yii migrate --migrationPath=@vendor/wdmg/yii2-subscribers/migrations`

# Configure
To add a module to the project, add the following data in your configuration file:

    'modules' => [
        ...
        'subscribers' => [
            'class' => 'wdmg\subscribers\Module',
            'routePrefix' => 'admin'
        ],
        ...
    ],


# Routing
Use the `Module::dashboardNavItems()` method of the module to generate a navigation items list for NavBar, like this:

    <?php
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
            'label' => 'Modules',
            'items' => [
                Yii::$app->getModule('subscribers')->dashboardNavItems(),
                ...
            ]
        ]);
    ?>

# Status and version [ready to use]
* v.1.0.8 - Added counter stats method
* v.1.0.7 - Fixed migrations and extend module dashboardNavItems() method
* v.1.0.6 - Update dependencies, README.md
* v.1.0.5 - Refactoring log of activity
* v.1.0.4 - Up to date dependencies
* v.1.0.3 - Refactoring import/export of subscribers
* v.1.0.2 - Fixed deprecated class declaration
* v.1.0.1 - Functionality for import/export of subscribers