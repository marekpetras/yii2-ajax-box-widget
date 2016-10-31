<?php

/**
 * @author Marek Petras <mark@markpetras.eu>
 * @link https://github.com/marekpetras/yii2-ajax-box-widget/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.0
 */

namespace marekpetras\yii2ajaxboxwidget;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the css/js files for the [[Box]] widget.
 *
 * @author Marek Petras
 */
class BoxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/marekpetras/yii2-ajax-box-widget/assets';
    public $js = [
        'jQuery.addObject.js',
        'adminlte.boxwidget.js',
        'marekpetras.ajaxbox.js',
    ];
    public $css = [
        'adminlte.box.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}