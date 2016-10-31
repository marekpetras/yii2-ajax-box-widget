<div id="<?=$box->getId()?>" class="<?=$box->getCssClasses()?>" data-source="<?=$box->source?>">

    <?php if ( $box->isVisible() ): ?>
        <div class="box-header with-border">
                <div class="box-tools">

                    <?=$box->renderTools()?>

                </div>


            <?php if ( $box->title ):?>
                <h3 class="box-title"><?=$box->title?>

                <?php if ( $box->subtitle ):?>
                    &nbsp;<small><?=$box->subtitle?></small>
                <?php endif; ?>

                </h3>
            <?php endif; ?>

        </div>
    <?php endif; ?>


    <div class="box-body">
        <?=$box->renderBody()?>
    </div>

    <?php if ( $box->hasFooter() && $box->isVisible() ): ?>
        <div class="box-footer clearfix">
            <?=$box->renderFooter()?>
        </div>
    <?php endif; ?>

</div>