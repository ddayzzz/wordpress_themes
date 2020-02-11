<?php if ( post_password_required() ) return;?>

<div id="comments" class="comments-area">
    <meta content="UserComments:<?php echo number_format_i18n( get_comments_number() );?>" itemprop="interactionCount">
    <?php if ( have_comments() ) : ?>

        <h3 class="uk-heading-line comments-title uk-margin-remove-top"><span><?php printf( _n( '1条评论', '%1$s条评论', get_comments_number(), 'aladdin' ),
                    number_format_i18n( get_comments_number() ) );?></span></h3>
        <div class="commentshow">
            <ul class="commentlist uk-comment-list">
                <?php wp_list_comments( array( 'callback' => 'mooc_comment', 'style' => 'ul' ) ); ?>
            </ul>
            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
                <nav class="commentnav" data-fuck="<?php echo $post->ID?>"><?php paginate_comments_links('prev_next=0');?></nav>
            <?php endif; ?>
        </div>
        <?php if ( ! comments_open() && get_comments_number() ) : ?>
            <p class="nocomments"><?php _e( 'Comments are closed.', 'aladdin' ); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(comments_open()) : ?>
        <div id="respond" class="respond" role="form">
            <h3 id="reply-title" class="uk-heading-line comments-title"><span>添加评论&nbsp;<small class="cancel-reply"><?php cancel_comment_reply_link(); ?></small></span></h3>
            <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
                <p><?php _e( 'You must be', 'aladdin' ); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e( 'logged in', 'aladdin' ); ?></a> <?php _e( 'to post a comment.', 'aladdin' ); ?></p>
            <?php else : ?>
                <form class="uk-form-horizontal" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                    <?php if ( $user_ID ) : ?>
                        <div class="uk-margin logged-in-as"><?php _e( 'Logged in as', 'aladdin' ); ?> <a href="<?php echo get_option('siteurl'); ?>/me/setting/"><?php echo $user_identity; ?></a> | <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e( 'Log out', 'aladdin' ); ?></a></div>

                    <?php else : ?>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: user"></span>
                                <input class="uk-input" id="author" name="author" type="text" aria-required="true" placeholder="昵称*" value="<?php echo $comment_author; ?>" >
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                <input class="uk-input" id="email" name="email" type="text" aria-required="true" placeholder="邮箱*" value="<?php echo $comment_author_email; ?>" >
                            </div>
                        </div>
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-1">
                                <span class="uk-form-icon" uk-icon="icon: link"></span>
                                <input class="uk-input" id="url" name="url" type="text" aria-required="true" placeholder="网站" value="<?php echo $comment_author_url; ?>" >
                            </div>
                        </div>

                    <?php endif; ?>


                    <div class="uk-margin">
                        <textarea class="uk-textarea" id="comment" name="comment" aria-required="true" rows="8" name="comment" placeholder="您此刻的想法"></textarea>
                    </div>

                    <div class="uk-margin smilies-wrap">
                        <?php echo get_wpsmiliestrans(); ?>
                    </div>

                    <div class="uk-margin">
                        <input class="uk-button <?php write_uikit_theme_comment_button() ?>" id="submit" type="submit" value="<?php _e( '发布', 'aladdin' ); ?>" name="submit">

                    </div>

                    <?php comment_id_fields(); ?>
                    <?php do_action('comment_form', $post->ID); ?>

                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    var x = document.getElementsByClassName('article-comment-info');
    for (var i=0; i<x.length; ++i) {
        if(x[i].childElementCount <= 0)
            continue;
        var url = x[i].firstElementChild.href;  // 跳转的链接, 微信头像在 author_comment_url 中, 需要替换
        if (url.match(/^https:\/\/wx.qlogo.cn\//) != null) {
            x[i].firstElementChild.href = '';
            x[i].parentNode.parentNode.firstElementChild.firstElementChild.src = url;
        }
    }
</script>