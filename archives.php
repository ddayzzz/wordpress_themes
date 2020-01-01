<?php
/*
Template Name: 归档
*/
?>

<?php get_header(); ?>
<div class="uk-container v-clearfix uk-margin-medium-top">
    <div class="uk-grid" data-ukgrid="">
        <div class="uk-width-2-3@l">
            <div class="uk-margin uk-card <?php write_uikit_theme_card(); ?> uk-card-body uk-card-hover">
                <?php while (have_posts()) : the_post(); ?>
                    <?php $tile_style = get_theme_cookie() == 'dark' ? 'secondary' : 'muted'; echo get_archive_by_category(true, $tile_style); ?>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="uk-width-1-3@l">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<div id="gotop"></div>
<?php get_footer(); ?>

