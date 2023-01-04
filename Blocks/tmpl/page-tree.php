<div class="page-tree">

    <a href="<?= get_the_permalink($props['pageTreeId']) ?>"> <h3><?= get_the_title($props['pageTreeId']) ?></h3> </a>
    
    <ul>

        <?php foreach($props['pages'] as $page) : ?>
        
        <?php // <?= for echo only ?>

        <a href="<?= get_the_permalink($page->ID) ?>"><li><?php echo $page->post_title ?></li></a>

        <?php endforeach ?>
        
    </ul>
    
</div>