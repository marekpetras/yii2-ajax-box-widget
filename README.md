# yii2-ajax-box-widget

Yii2 Ajax Box Widget
========================

About
-----

Style taken from the fabulous AdminLte bootstrap template, however,
since only using tiny portion of it, I made it standalone, all the design creadits go to there though.

https://almsaeedstudio.com/themes/AdminLTE/index2.html

Depending also on the http://github.com/rpflorence/jQuery.addObject plugin to manage jquery objects

Buttons are by default rendered with fa icons, I not going to add the dependency, just install them yourself or do your own buttons. For example https://github.com/rmrevin/yii2-fontawesome

Scroll all the way down to see box reloaded with form data.

![boxes.png](/sample/boxes.png)

Fully supports grids:

![4grids.png](/sample/4grids.png)
```php
<?php
use marekpetras\yii2ajaxboxwidget\Box;
?>

<div class="row">
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Users','bodyLoad'=>['/admin/user']])?></div>
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Log','bodyLoad'=>['/admin/log']])?></div>
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Jobs','bodyLoad'=>['/admin/job']])?></div>
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Queue','bodyLoad'=>['/admin/queue']])?></div>
</div>
```

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist marekpetras/yii2-ajax-box-widget "^1.0"
```

or add

```
"marekpetras/yii2-ajax-box-widget": "^1.0"
```

to the require section of your `composer.json` file.

Usage
-----


Two types of usages, remote or local

Remote:
```php
<?php

echo marekpetras\yii2ajaxboxwidget\Box::widget($options);

?>
```

Local:
```php
<?php

$box = marekpetras\yii2ajaxboxwidget\Box::begin($options);
// write body content here
echo 'Body';
// add some tools
$box->tools();
echo 'Tools';
// add some footer
$box->footer();
echo 'Footer';
$box->end();

?>
```

Possible Options:
```php
<?php

$options = [
    'title' => 'My Box',
    'subtitle' => 'About us',
    'type' => 'info',
    'invisible' => false,
    'bodyLoad' => ['test/my', 'var' => 'value'],

    'toolsTemplate' => '{tools} {reload} {collapse} {remove} {myButton}',
    'toolsButtons' => [
        'myButton' => function() {
            return \yii\helpers\Html::button('my button');
        },
    ],
    'toolsButtonOptions' => [
        'class' => 'myButtons',
    ],
    'autoload' => true,
    'hidden' => false,
    'data' => [
        'postvar1' => 123,
        'postvar2' => 234,
    ],
    'clientOptions' => [
       'autoload' => true, // modify this with the general option not here though
       'onerror' => new \yii\web\JsExpression('function(response, box, xhr) {console.log(response,box,xhr)}'), // loads the error message in the box by default
       'onload' => new \yii\web\JsExpression('function(box, status) { console.log(box,status); }'), // nothing by default
    ],
    'classes' => ['box', 'box-flat', 'box-init'],
    'view' => '@path/to/your/view',
];
?>
```

Js Usage
```js

$('#boxId').box('reload', data); // reloads content from predefined source with, if you choose so, ammended data - good for filtering etc
$('#boxId').box('source', newSource); // sets new box source
$('#boxId').box('show|hide|toggle'); // show hide toggle using jquery
$('#boxId').box('addOverlay|removeOverlay'); // add/removes overlay indicating load

```

I usually overload render() in my controllers to detect ajax by itself and just load whichever action that I want properly.

```php
<?php

class BaseController extends \yii\base\Controller
{

    /**
     * @inheritdoc
     */
    public function render($view, $params = [])
    {
        if ( Yii::$app->request->getIsAjax() ) {
            Yii::trace('Rendering AJAX');
            return parent::renderAjax($view, $params);
        }
        else {
            return parent::render($view, $params);
        }
    }
}
```

Sample
------

[dashboard.php](/sample/dashboard.php)

![boxes.png](/sample/boxes.png)

```php
<?php

use marekpetras\yii2ajaxboxwidget\Box;
use yii\helpers\Html;

?>


<div class="health-default-index">
    <?php $box = Box::begin(['title'=>'Select clients and date range']); ?>

    <?=Html::beginForm(['report'],'post',['id'=>'healthcheckform','layout'=>'inline']); ?>

    <div class="row">
        <div class="col-sm-5">
            <select>
                <options>
            </select>
        </div>
        <div class="col-sm-5">
            <input type="date" />
        </div>
        <div class="col-sm-2">
        <?=Html::submitButton('Apply',['class'=>'btn btn-primary btn-flat btn-block'])?>
        </div>
    </div>

    <?=Html::endForm()?>

    <?php $box->end()?>

    <div id="report">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?=Box::widget(['invisible'=>true,'title'=>'Target Cost','bodyLoad'=>['target-vs-actual','metric'=>'Cost'],'autoload'=>false,'hidden'=>true])?>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?=Box::widget(['invisible'=>true,'title'=>'Target Actions','bodyLoad'=>['target-vs-actual','metric'=>'Actions'],'autoload'=>false,'hidden'=>true])?>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?=Box::widget(['invisible'=>true,'title'=>'Target CPA','bodyLoad'=>['target-vs-actual','metric'=>'CostPerAction'],'autoload'=>false,'hidden'=>true])?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 pull-right">
                <?=Box::widget(['title'=>'Highest Spending Search Queries','bodyLoad'=>['highest-spending-search-queries'],'autoload'=>false,'hidden'=>true])?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 pull-left">
                <?=Box::widget(['title'=>'Network Performance','bodyLoad'=>['network-performance'],'autoload'=>false,'hidden'=>true])?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 pull-left">
                <?=Box::widget(['title'=>'Device Performance','bodyLoad'=>['device-performance'],'autoload'=>false,'hidden'=>true])?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <?=Box::widget(['title'=>'Brand % of Spend','bodyLoad'=>['brand-spend'],'autoload'=>false,'hidden'=>true])?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <?=Box::widget(['title'=>'Match type % of Spend','bodyLoad'=>['match-type-spend'],'autoload'=>false,'hidden'=>true])?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <?=Box::widget(['title'=>'Lost Impression Share','bodyLoad'=>['lost-impression-share'],'autoload'=>false,'hidden'=>true])?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <?=Box::widget(['title'=>'Average Quality Score','bodyLoad'=>['average-quality-score'],'autoload'=>false,'hidden'=>true])?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?=Box::widget(['title'=>'Expected CTR <small>drillthrough to most contributing campaigns</small>','bodyLoad'=>['expected-ctr'],'autoload'=>false,'hidden'=>true])?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?=Box::widget(['title'=>'Ad Relevance <small>drillthrough to most contributing campaigns</small>','bodyLoad'=>['ad-relevance'],'autoload'=>false,'hidden'=>true])?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?=Box::widget(['title'=>'Landing Pages Experience <small>drillthrough to most contributing campaigns</small>','bodyLoad'=>['landing-pages-experience'],'autoload'=>false,'hidden'=>true])?>
            </div>
        </div>
    </div>

<?php $this->registerJs("
    $('#healthcheckform').submit(function(e){
        e.preventDefault();
        var data = $(this).serialize(); // this will be loaded via post on submit
        $('#report .box-init').each(function(){
            $(this).box('reload',data).box('show');
        });
    });
");?>
```
