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
        var data = $(this).serialize();
        $('#report .box-init').each(function(){
            $(this).box('reload',data).box('show');
        });
    });
");?>