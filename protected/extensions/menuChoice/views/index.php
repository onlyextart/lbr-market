<div class="left-menu-wrapper grey">
    <?php if(!empty($types)): ?>
    <div class="label">КАТАЛОГ ТОВАРОВ</div>
    <div class="l-menu-wrapper">
        <ul class="accordion" id="accordion-type">
            <?php foreach($types as $type) :
                $typeHref = '/catalog'.$type['path'].'/';
            ?>
            <li elemId="<?php echo $type['id'] ?>">
                <?php if($type['id'] == Yii::app()->params['currentType']): ?>
                <a href="<?php echo $typeHref ?>" class="active">
                    <span class="icon"></span>
                    <span><?php echo $type['name'] ?></span>
                </a>
                <?php else: ?>
                <a href="<?php echo $typeHref ?>">
                    <span><?php echo $type['name'] ?></span>
                </a>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>