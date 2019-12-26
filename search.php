
<?php get_header(); ?>
<div class="uk-container v-clearfix uk-margin-medium-top">
    <div class="uk-grid" data-ukgrid="">
        <div class="uk-width-2-3@l">
            <h1 class="uk-heading-divider article-title"><?php echo get_search_query().'的搜索结果'; ?></h1>
            <?php if ( have_posts() ) : ?>

                    <?php
                    while ( have_posts() ) : the_post();
                        get_template_part( 'content', 'archive' );
                    endwhile;
                    ?>
                <?php
                $pag = paginate_links(array(
                    'prev_next' => 0,
                    'before_page_number' => '',
                    'mid_size' => 2,
                    'type'=> 'array'
                ));
                if(count($pag) >= 2){
                    echo '<div class="uk-tile uk-tile-default uk-padding-remove"><div class="navigation uk-margin-small-top uk-margin-small-bottom">' . join(' ', $pag) . '</div></div>';
                }
                ?>
            <?php else: ?>
                <div class="uk-alert-warning uk-margin-medium" uk-alert>
                    <p>没有 “<?php echo get_search_query();?> ”的结果</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="uk-width-1-3@l">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<div id="gotop"></div>
<?php get_footer(); ?>


