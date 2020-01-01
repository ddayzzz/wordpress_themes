


<?php get_header(); ?>
<div class="uk-container v-clearfix uk-margin-medium-top">
    <div class="uk-grid" data-ukgrid="">
        <div class="uk-width-2-3@l">
            <?php if(get_theme_cookie() == 'light'): ?>
                <div class="uk-alert-warning uk-margin-large@m uk-margin-small-top" uk-alert>
                    <h1>404 NOT FOUND!</h1>
                </div>
            <?php else: ?>
                <div class="uk-container uk-margin-large@m uk-margin-small-top">
                    <div class="uk-panel uk-padding-small" style="background: #757575">
                        <h1>404 NOT FOUND!</h1>
                    </div>
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

