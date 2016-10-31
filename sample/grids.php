<?php
use marekpetras\yii2ajaxboxwidget\Box;
?>

<div class="row">
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Users','bodyLoad'=>['/admin/user']])?></div>
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Log','bodyLoad'=>['/admin/log']])?></div>
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Jobs','bodyLoad'=>['/admin/job']])?></div>
    <div class="col-sm-12 col-md-6"><?=Box::widget(['title'=>'Queue','bodyLoad'=>['/admin/queue']])?></div>
</div>