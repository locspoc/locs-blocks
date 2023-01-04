<div class="featured-page">

    <?php 

        $featuredPage = new WP_Query(array(
            'post_type' => 'page',
            'p' => $props['featuredPageId']
        ));

        while($featuredPage->have_posts()) {

            $featuredPage->the_post(); ?>

                <div class="featured-page-callout">

                    <div class="featured-page-callout__photo" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
                    
                    </div>

                    <div class="featured-page-callout__text">

                        <h4>About: <?php esc_html(the_title()); ?></h4>

                        <p><?php echo wp_strip_all_tags(wp_trim_words(get_the_content()), 60); ?></p>

                        <p><strong><a href="<?php the_permalink(); ?>">Learn more about <?php esc_html(the_title()); ?> &raquo; </a></strong></p>

                    </div>

                </div>

            <?php 

            wp_reset_postdata();

        }

    ?>

</div>