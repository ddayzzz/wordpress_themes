<?php get_header(); ?>
    <div class="uk-container v-clearfix uk-margin-medium-top">
        <div class="uk-grid" data-ukgrid="">
            <div class="uk-width-2-3@m posts">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('content', get_post_format()); ?>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php if (is_home()) { ?>
                    <div class="uk-container loadmore"><?php ajax_show_more_button(); ?></div>
                <?php } else {
                    $pag = paginate_links(array(
                        'prev_next' => 0,
                        'before_page_number' => '',
                        'mid_size' => 2,
                        'type'=> 'array'
                    ));
                    if($pag != null && count($pag) >= 2){
                        echo '<div class="uk-tile uk-tile-default uk-padding-remove"><div class="navigation uk-margin-small-top uk-margin-small-bottom">' . join(' ', $pag) . '</div></div>';
                    }
                 } ?>
            </div>
            <div class="uk-width-1-3@m">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
<!--在移动端显示的导航栏-->
<?php get_footer(); ?>
<script type="text/javascript">
    // function executeHomeOffCanvasNavbar() {
    //     UIkit.offcanvas("#offcanvas-single-nav").show();
    // }
    // 定义siderbar 的菜单篮
    // var sidebar = document.getElementsByClassName('sidebar')[0];
    // document.getElementById('index-head-category-mobile').appendChild(sidebar.cloneNode(true));
</script>
