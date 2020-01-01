<div class="uk-card <?php write_uikit_theme_card() ?> uk-card-hover uk-padding-remove-top uk-margin-small-bottom">

    <?php $thumb_img = mooc_thumbnail_postflow_url(); ?>
    <?php if ($thumb_img == NULL or post_password_required()): ?>
        <!--没有缩略图-->
        <div class="uk-card-header">
            <div class="uk-grid-small uk-padding-remove" uk-grid>
                <a class="uk-width-expand uk-display-block uk-link-reset" href="<?php the_permalink(); ?>">
                    <span class="uk-text-lead"><?php the_title(); ?></span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <!--有缩略图-->
        <div class="uk-card-header uk-padding-remove">
                <div class="uk-section uk-light uk-background-cover uk-padding-remove-top" style="background-image: url(<?php echo $thumb_img; ?>)">
                    <div class="uk-panel uk-padding uk-background-secondary uk-light">
                        <a class="uk-width-expand uk-display-block uk-link-reset" href="<?php the_permalink(); ?>">
                            <span class="uk-text-lead"><?php the_title(); ?></span>
                        </a>
                    </div>
                </div>
        </div>

    <?php endif; ?>
    <!--摘要信息-->
    <div class="uk-card-body"><span>
        <p><?php echo post_password_required() ? '>已加密<' :  mb_strimwidth(strip_tags($post->post_content), 0, 260, "..."); ?></p>
        <p><?php echo get_the_date('Y年n月j日'); ?> - <?php if (function_exists('get_the_views')) echo get_the_views($post->ID); ?>次阅读 - <?php echo get_post($post->ID)->comment_count ?>条评论<?php edit_post_link('编辑', ' - '); ?></p></span>
    </div>
</div>