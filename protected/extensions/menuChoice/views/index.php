<div class="left-menu-wrapper grey">
    <ul class="accordion" id="accordion-group">
        <li><a href="#">ГСМ и технические жидкости</a>
            <ul>
                <li><a href="#">Масла 1</a></li>
                <li><a href="#">Масла 2</a></li>
            </ul>
        </li>
        <li><a href="#">Фильтры</a>
            <ul>
                <li><a href="#">Масла 3</a></li>
                <li><a href="#">Масла 4</a></li>
            </ul>
        </li>
        <li><a href="#">Ремни</a>
            <ul>
                <li><a href="#">Масла 3</a></li>
                <li><a href="#">Масла 4</a></li>
            </ul>
        </li>
        <li><a href="#">Колеса и комплекты</a>
            <ul>
                <li><a href="#">Масла 3</a></li>
                <li><a href="#">Масла 4</a></li>
            </ul>
        </li>
    </ul>
    
    <?php if(!empty($filterCategory) || !empty($filterMaker)): ?>
    <div class="rounded">
        <div class="label">Текущий отбор</div>
        <ul class="filter-links">
            <?php
               $delTypeHref = $delMakerHref = Yii::app()->getBaseUrl(true);
               if(!empty(Yii::app()->params['currentMaker'])){
                   $delTypeHref = '/manufacturer'.$makers[Yii::app()->params['currentMaker']]['path'].'/';
               }
               if(!empty(Yii::app()->params['currentType'])){
                   $delMakerHref = '/catalog'.$types[Yii::app()->params['currentType']]['path'].'/';
               }
            ?>
            <?php if(!empty($filterCategory)): ?>            
            <li elem="type"><a href="<?php echo $delTypeHref ?>"><?php echo $filterCategory['name']; ?></a></li>
            <?php endif; ?>
            <?php if(!empty($filterMaker)): ?>
            <li elem="maker"><a href="<?php echo $delMakerHref ?>"><?php echo $filterMaker['name']; ?></a></li>
            <?php endif; ?>
        </ul>
        <a href="<?php echo Yii::app()->getBaseUrl(true) ?>" id="cancel-filter">Сбросить фильтры</a>
    </div>
    <?php endif; ?>
    <?php if(!empty($types)): ?>
    <div class="label">Выбор по типу техники:</div>
    <div class="l-menu-wrapper">
        <ul class="accordion" id="accordion-type">
            <?php foreach($types as $type) : ?>
            <?php
                $typeHref = '/catalog'.$type['path'].'/';
                if(!empty(Yii::app()->params['currentMaker'])){
                    $typeHref = '/catalog'.$type['path'].$makers[Yii::app()->params['currentMaker']]['path'].'/';
                }
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
    <?php if(!empty($makers)): ?>
    <div class="label">Выбор по производителю техники:</div>
    <div class="l-menu-wrapper">
        <ul class="accordion" id="accordion-maker">
            <?php foreach($makers as $maker): ?>
            <?php
                $makerHref = '/manufacturer'.$maker['path'].'/';
                if(!empty(Yii::app()->params['currentType'])){
                    $makerHref = '/catalog'.$types[Yii::app()->params['currentType']]['path'].$maker['path'].'/';
                }
            ?>
            <li elemId="<?php echo $maker['id'] ?>">
                <?php if($maker['id'] == Yii::app()->params['currentMaker']): ?>
                <a href="<?php echo $makerHref ?>" class="active">
                    <span class="icon"></span>
                    <span><?php echo $maker['name'] ?></span>
                </a>
                <?php else: ?>
                <a href="<?php echo $makerHref ?>">
                    <span><?php echo $maker['name'] ?></span>
                </a>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
