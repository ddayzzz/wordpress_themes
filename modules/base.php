<?php
/**
 * 主题初始化
 *
 * @since puu 1.0.0
 */
function mooc_widgets_init()
{
    register_sidebar(array(
        'name' => 'Main Sidebar',
        'id' => 'sidebar-1',
        'description' => '首页边栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h4 class="uk-heading-line uk-text-bold"><span>',
        'after_title' => '</span></h4>',
    ));
    register_sidebar(array(
        'name' => 'Single Sidebar',
        'id' => 'sidebar-2',
        'description' => '内页边栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'mooc_widgets_init');

function mooc_setup()
{
    register_nav_menu('primary', __('基本菜单', 'Mooc'));
    add_filter('pre_option_link_manager_enabled', '__return_true');
    add_editor_style();
    remove_filter('the_content', 'wptexturize');
}

add_action('after_setup_theme', 'mooc_setup');


/**
 * 引入样式文件及JS
 *
 * @since puu 1.1.0
 */
function mooc_scripts_styles()
{
    global $wp_styles;
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('ajax-comment', get_template_directory_uri() . '/assets/js/mooc.js', array('jquery'), '20150620', true);
    wp_localize_script('ajax-comment', 'ajaxcomment', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('wp_enqueue_scripts', 'mooc_scripts_styles');

/* 引入 uikit 库*/
function shu_add_externel_modules()
{
    /** uikit */
    wp_enqueue_style('uikit-css', get_template_directory_uri() . '/assets/css/uikit.min.css');
    wp_enqueue_script('uikit-js', get_template_directory_uri() . '/assets/js/uikit.min.js', '3.2.6', true);
//    wp_enqueue_script('vue-develop-js', get_template_directory_uri() . '/vue/vue.js', '2.6.1', true);
//    wp_enqueue_script('axios-js', get_template_directory_uri() . '/axios/axios.min.js', '0.12.0', true);
    // https://github.com/uikit/uikit/issues/2229#issuecomment-421405322
    wp_enqueue_script('uikit-js-icons', get_template_directory_uri() . '/assets/js/uikit-icons.min.js', '3.2.6', true);
    if(get_theme_cookie() == 'dark')
    {
        wp_enqueue_style('theme-dark-css', get_template_directory_uri() . '/assets/css/dark-theme.css');
    }
    else{
        wp_enqueue_style('theme-light-css', get_template_directory_uri() . '/assets/css/light-theme.css');
    }
}

add_action('wp_enqueue_scripts', 'shu_add_externel_modules');

/**
 * WordPress 更改文章密码保护后显示的提示内容
 * https://wordpress.org/support/article/using-password-protection/
 */
function password_protected_change() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $output = '
        <form class="uk-form-stack uk-margin-large" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
            <div class="uk-margin">
                <label class="uk-form-label" for="' . $label . '">'.__( "这是一篇受密码保护的文章，您需要提供访问密码：" ).'</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="' . $label . '" type="password" placeholder="" name="post_password">
                 </div>
            </div>
            <div class="uk-margin uk-text-right">
                <input class="uk-button uk-button-primary" id="post_password_submit" type="submit" value="' . esc_attr__( "提交" ) . '" name="Submit">
            </div>
        </form>
        ';
    return $output;
}
add_filter( 'the_password_form','password_protected_change' );

// function modify_read_more_link() {
// 	return '<a class="uk-button uk-button-default more-link" href="' . get_permalink() . '">Read More</a>';
// }
// add_filter( 'the_content_more_link', 'modify_read_more_link' );

/**
 * 浏览次数统计功能
 *
 * @since puu 1.0.0
 */
function set_post_views()
{
    global $post;
    $post_id = intval($post->ID);
    $count_key = 'views';
    $views = get_post_custom($post_id);
    $views = intval($views['views'][0]);
    if (!is_user_logged_in()) {
        if (is_single() || is_page()) {
            if (!update_post_meta($post_id, 'views', ($views + 1))) {
                add_post_meta($post_id, 'views', 1, true);
            }
        }
    }
}

add_action('get_header', 'set_post_views');
function get_the_views($post_id)
{
    $count_key = 'views';
    $views = get_post_custom($post_id);
    $views = intval($views['views'][0]);
    $post_views = intval(post_custom('views'));
    if ($views == '') {
        return '0';
    } else {
        return $views;
    }
}

/**
 * 显示打赏按钮
 *
 * @since bigsize 1.0.1
 */
function reward_button()
{
    if (get_option('mooc_reward') != 'checked') return;
    ?>
    <div style="margin: 20px auto;text-align: center">
        <small><?php echo get_option('mooc_reward_description') ?></small><br>
        <a class="reward"
           onclick="var qr = document.getElementById('QR'); if (qr.style.display === 'none') {qr.style.display='block';} else {qr.style.display='none'}"
           disable="enable"></a>
        <div id="QR" style="display: none">
            <a id="wechat" style="display: inline-block;width: 33.33%"
               href="<?php echo get_template_directory_uri() . '/wechat.png' ?>" rel="group">
                <img id="wechat_qr" alt="WeChatPay" style="max-width:100%"
                     src="<?php echo get_template_directory_uri() . '/wechat.png' ?>"></a>
            <a id="alipay" style="display: inline-block;width: 33.33%"
               href="<?php echo get_template_directory_uri() . '/alipay.png' ?>" rel="group">
                <img id="alipay_qr" alt="Alipay" style="max-width:100%"
                     src="<?php echo get_template_directory_uri() . '/alipay.png' ?>"></a>
        </div>
    </div>
    <?php
}

/**
 * emoji
 *
 * @since bigsize 1.0.7
 */
function get_wpsmiliestrans()
{
    global $wpsmiliestrans;
    $wpsmilies = array_unique($wpsmiliestrans);
    foreach ($wpsmilies as $alt => $src_path) {
        $emoji = str_replace(array('&#x', ';'), '', wp_encode_emoji($src_path));
        if ($emoji == 'mrgreen.png') continue;
        $output .= '<a class="add-smily" data-smilies="' . $alt . '"><img class="wp-smiley" src="https://s.w.org/images/core/emoji/11/svg/' . $emoji . '.svg" style="width: 24px !important;height: 24px !important;"/></a>';
    }
    return $output;
}

/**
 * Ajax评论提交
 *
 * @since puu 1.1.0
 *
 * @require Wordpress 4.4
 * @updated by bigfa
 */
function fa_ajax_comment_scripts()
{
    wp_localize_script('ajax-comment', 'ajaxcomment', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'order' => get_option('comment_order'),
        'formpostion' => 'bottom', // 默认为bottom，如果你的表单在顶部则设置为top
    ));
}

function fa_ajax_comment_err($a)
{
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

function fa_ajax_comment_callback()
{
    $comment = wp_handle_comment_submission(wp_unslash($_POST));
    if (is_wp_error($comment)) {
        $data = $comment->get_error_data();
        if (!empty($data)) {
            fa_ajax_comment_err($comment->get_error_message());
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment;
    // 主题评论结构
    ?>
    <li <?php comment_class(); ?> <?php if( $depth > 2){ echo ' style="margin-left:-100px;"';} ?> id="li-comment-<?php comment_ID() ?>" itemscope="" itemprop="comment">
    <article class="uk-comment <?php if($depth <= 1 ){echo 'uk-comment-primary';}?> uk-visible-toggle comment-block">
        <header class="uk-comment-header uk-position-relative">
            <div class="uk-grid-medium uk-flex-middle" uk-grid>
                <div class="uk-width-auto">
                    <img class="uk-comment-avatar article-comment-avatar-img" src="<?php echo get_avatar_url($comment, $size = '56'); ?>"
                         width="56" height="56" alt="">
                </div>
                <div class="uk-width-expand">
                    <h4 class="uk-comment-title uk-margin-remove article-comment-info"><?php
                        $url = get_comment_author_url();
                        $author = get_comment_author();
                        if (empty($url)) echo $author;
                        else
                            echo '<a class="uk-link-reset" href="' . $url . '" target="_blank">' . $author . '</a>'; ?></h4>
                    <p class="uk-comment-meta uk-margin-remove-top"><?php echo get_comment_date('Y年n月j日'); ?></p>
                </div>
            </div>
            <div class="uk-position-top-right uk-position-small uk-hidden-hover reply"><?php comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
        </header>
        <div class="uk-comment-body">
            <p><?php comment_text(); ?></p>
        </div>
    </article>
    <?php die();
}

add_action('wp_enqueue_scripts', 'fa_ajax_comment_scripts');
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');


/**
 * 评论回调
 *
 * @since ok 1.0.2
 */
function mooc_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    switch ($comment->comment_type) :
        case 'pingback' :
        case 'trackback' :
            ?>
            <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <p><?php _e('Pingback:', 'mooc_comment'); ?><?php comment_author_link(); ?> </p>
            <?php
            break;
        default :
            global $post;
            ?>
            <li <?php comment_class(); ?><?php if ($depth > 3) {
            $d = 100;
            $n = $depth - 3;
            echo ' style="margin-left:-' . $d * $n . 'px;"';
        } ?> id="li-comment-<?php comment_ID() ?>" itemscope="" itemprop="comment">
            <article class="uk-comment <?php if($depth <= 1 ){echo 'uk-comment-primary';}?> uk-visible-toggle comment-block">
                <header class="uk-comment-header uk-position-relative">
                    <div class="uk-grid-medium uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img class="uk-comment-avatar article-comment-avatar-img" src="<?php echo get_avatar_url($comment, $size = '56'); ?>"
                                 width="56" height="56" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h4 class="uk-comment-title uk-margin-remove article-comment-info"><?php
                                $url = get_comment_author_url();
                                $author = get_comment_author();
                                if (empty($url)) echo $author;
                                else
                                    echo '<a class="uk-link-reset" href="' . $url . '" target="_blank">' . $author . '</a>'; ?></h4>
                            <p class="uk-comment-meta uk-margin-remove-top"><?php echo get_comment_date('Y年n月j日'); ?></p>
                        </div>
                    </div>
                    <div class="uk-position-top-right uk-position-small uk-hidden-hover"><?php comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
                </header>
                <div class="uk-comment-body">
                    <p><?php comment_text(); ?></p>
                </div>
            </article>
            <?php
            break;
    endswitch;
}


/**
 * 评论邮件回复（SMTP & wp_mail）
 *
 * @since puu 1.3.0
 */
add_action('phpmailer_init', 'mail_smtp');
function mail_smtp($phpmailer)
{
    $phpmailer->IsSMTP();
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = get_option('mooc_smtp_port');
    $phpmailer->SMTPSecure = "ssl";
    $phpmailer->Host = get_option('mooc_smtp_host');
    $phpmailer->Username = get_option('mooc_smtp_username');
    $phpmailer->Password = get_option('mooc_smtp_password');
}

function comment_mail_notify($comment_id)
{
    if (get_option('mooc_send_notify_mail') != 'checked') return;
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $spam_confirmed = $comment->comment_approved;
    if (($parent_id != '') && ($spam_confirmed != 'spam')) {
        // $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $wp_email = get_option('mooc_smtp_username');
        $to = trim(get_comment($parent_id)->comment_author_email);
        $subject = '您在[' . get_option('blogname') . ']的留言有了新回复';
        $message = '
        	<div align="center" style="background-color: #eee; color: #777; padding:66px 0px;">
        	<div style="text-align: left; background-color: #fff; width: 700px">
		<h2 style="  text-align: center;padding-top: 40px;border-top: #777 solid 3px;">' . $subject . '</h2>
		<div style="margin: 0px 60px 30px;  padding: 30px 0;border-top: #777 solid 1px;border-bottom: #ddd dashed 2px;">
		<p><strong>' . trim(get_comment($parent_id)->comment_author) . '，你好!</strong></p>
		<p class="message"><strong>您曾在《' . get_the_title($comment->comment_post_ID) . '》留言：</strong><br />'
            . trim(get_comment($parent_id)->comment_content) . '</p>
		<p><strong>' . trim($comment->comment_author) . ' 给你的回复是：</strong><br />'
            . trim($comment->comment_content) . '<br /></p>
		<p>你可以点击此链接 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看完整内容</a> | 欢迎再次来访<a href="' . home_url() . '">' . get_option('blogname') . '</a></p></div>
		<div style="padding-bottom: 30px;  margin: 0px auto;  width: 580px;">
		<p style="color: #aaa; font-size: 12px">©' . get_option('blogname') . '</p>
		<p style="color: #aaa; font-size: 12px">' . get_bloginfo('description') . '</p>
		</div></div></div>';
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}

add_action('comment_post', 'comment_mail_notify');


/**
 * Ajax文章无限加载（通过按钮）
 *
 * @since puu 1.1.5
 */
add_action('wp_ajax_nopriv_ajax_index_post', 'ajax_index_post');
add_action('wp_ajax_ajax_index_post', 'ajax_index_post');
function ajax_index_post()
{
    $paged = $_POST["paged"];
    $total = $_POST["total"];
    $category = $_POST["category"];
    $author = $_POST["author"];
    $tag = $_POST["tag"];
    $search = $_POST["search"];
    $the_query = new WP_Query(array("posts_per_page" => get_option('posts_per_page'), "cat" => $category, "tag" => $tag, "author" => $author, "post_status" => "publish", "post_type" => "post", "paged" => $paged, "s" => $search));
    while ($the_query->have_posts()) {
        $the_query->the_post();
        get_template_part('content', get_post_format());
    }
    wp_reset_postdata();
    $nav = '';
    if ($category) $cat_id = ' data-cate="' . $category . '"';
    if ($author) $author = ' data-author="' . $author . '"';
    if ($tag) $tag = ' data-tag="' . $tag . '"';
    if ($search) $search = ' data-search="' . $search . '"';
    if ($total > $paged) $nav = '<div class="loadmore"><a id="show-more"' . $cat_id . $author . $search . ' data-total="' . $total . '" data-paged = "' . ($paged + 1) . '" class="show-more m-feed--loader"></a></div>';
    echo $nav;
    die;
}

function ajax_show_more_button()
{
//    global $wp_query;
    if (2 >  wp_count_posts()->publish) {
        return;
    }
    if (is_category()) $cat_id = ' data-cate="' . get_query_var('cat') . '"';
    if (is_author()) $author = ' data-author="' . get_query_var('author') . '"';
    if (is_tag()) $tag = ' data-tag="' . get_query_var('tag') . '"';
    if (is_search()) $search = ' data-search="' . get_query_var('s') . '"';
    echo '<a id="show-more"' . $cat_id . ' data-paged = "2"' . $author . $tag . $search . ' data-total="' . $GLOBALS["wp_query"]->max_num_pages . '" class="show-more m-feed--loader"></a>';
}

/**
 * 自定义侧栏小工具
 *
 * 热门文章、热评文章
 *
 * @since puu 1.3.0
 */
class widget_mostview extends WP_Widget
{
    function widget_mostview()
    {
        $widget_options = array('classname' => 'set_contact', 'description' => '显示近期热门文章（来自主题）');
        $this->WP_Widget(false, '近期热门*', $widget_options);
    }

    function widget($args, $instance)
    {
        extract($args);
        echo $before_widget;
        echo $before_title . '近期热门' . $after_title;
        ?>
        <ul class="uk-list">
            <?php
            $ode = '';
            $limit = 10;    // 显示文章数
            $days = 80;    // 时间限制
            global $wpdb, $post;
            $output = '';
            // database query
            $most_viewed = $wpdb->get_results("SELECT ID, post_date, post_title, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) $inr_join WHERE post_status = 'publish' AND post_password = ''  AND post_type='post' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days AND meta_key = 'views' GROUP BY ID ORDER BY views DESC LIMIT $limit");
            if ($most_viewed) {
                foreach ($most_viewed as $viewed) {
                    $post_ID = $viewed->ID;
                    $post_views = number_format($viewed->views);
                    $post_title = esc_attr($viewed->post_title);
                    $get_permalink = esc_attr(get_permalink($post_ID));
                    $output .= "<li><a href= \"" . $get_permalink . "\" rel=\"bookmark\" title=\"" . $post_title . "\">" . $post_title . "</a> (" . $post_views . ")</li>";
                }
            } else {
                $output = "无数据";
            }
            echo $output;
            ?>
        </ul>
        <?php echo $after_widget;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("widget_mostview");'));

class widget_mostcomments extends WP_Widget
{
    function widget_mostcomments()
    {
        $widget_options = array('classname' => 'set_contact', 'description' => '显示近期热评文章（来自主题）');
        $this->WP_Widget(false, '热评文章*', $widget_options);
    }

    function widget($args, $instance)
    {
        extract($args);
        echo $before_widget;
        echo $before_title . '热评文章' . $after_title;
        ?>
        <ul class="uk-list">
            <?php
            $posts_num = 8;    // 显示文章数
            $days = 80;    // 时间限制
            global $wpdb;
            $sql = "SELECT ID , post_title , comment_count FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND post_password = '' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days ORDER BY comment_count DESC LIMIT 0 , $posts_num ";
            $posts = $wpdb->get_results($sql);
            $output = "";
            foreach ($posts as $post) {
                $output .= "\n<li><a href= \"" . get_permalink($post->ID) . "\" rel=\"bookmark\" title=\"" . $post->post_title . "\">" . $post->post_title . "</a> (" . $post->comment_count . ")</li>";
            }
            echo $output;
            ?>
        </ul>
        <?php echo $after_widget;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("widget_mostcomments");'));


/**
 * 添加主题设置页面
 *
 * @since puu 1.3.0
 */
if ($_POST['update_themeoptions'] == 'true') {
    themeoptions_update();
}
function themeoptions_page()
{
    ?>
    <div class="wrap">
        <div id="icon-themes" class="icon32"><br/></div>
        <h1>主题设置</h1>
        <form method="POST" action="">
            <input type="hidden" name="update_themeoptions" value="true"/>
            <h2>个性设置</h2>
            <p><input type="text" name="copyright" id="copyright" size="32"
                      value="<?php echo get_option('mooc_copyright'); ?>"/> 底部版权信息</p>
            <p><label><input type="checkbox" name="reward" id="reward" <?php echo get_option('mooc_reward'); ?>/> 显示打赏按钮</label>
            </p>
            <p><input type="text" name="reward_description" id="reward_description" size="32"
                      value="<?php echo get_option('mooc_reward_description'); ?>"/> 打赏提示信息</p>
            <h2>SMTP设置</h2>
            <h4><label><input type="checkbox" name="send_notify_mail"
                              id="send_notify_mail" <?php echo get_option('mooc_send_notify_mail'); ?> />
                    回复评论后发送通知邮件</label></h4>
            <p><input type="text" name="smtp_host" id="smtp_host" size="32"
                      value="<?php echo get_option('mooc_smtp_host'); ?>"/>* Host (例如smtp.qq.com)</p>
            <p><input type="text" name="smtp_port" id="smtp_port" size="32"
                      value="<?php echo get_option('mooc_smtp_port'); ?>"/>* Port (例如465)</p>
            <p><input type="text" name="smtp_username" id="smtp_username" size="32"
                      value="<?php echo get_option('mooc_smtp_username'); ?>"/>* 邮箱账户 (例如88888@qq.com)</p>
            <p><input type="password" name="smtp_password" id="smtp_password" size="32"
                      value="<?php echo get_option('mooc_smtp_password'); ?>"/>* 邮箱密码</p>
            <h2>高级设置</h2>
            <p> 统计脚本（需要 &lt;script&gt;）</p>
            <textarea name="stat_js" id="stat_js"
                      style="width:80%; height:120px"><?php echo htmlspecialchars(get_option('mooc_stat_js')); ?></textarea>
            <p><input type="submit" class="button-primary" name="bcn_admin_options" value="更新数据"/></p>
        </form>
    </div>
    <?php
}

function themeoptions_update()
{
    // 数据更新验证
    update_option('mooc_copyright', $_POST['copyright']);
    if ($_POST['reward'] == 'on') {
        $t = 'checked';
    } else {
        $t = '';
    }
    update_option('mooc_reward', $t);
    update_option('mooc_reward_description', $_POST['reward_description']);
    update_option('mooc_smtp_host', $_POST['smtp_host']);
    update_option('mooc_smtp_port', $_POST['smtp_port']);
    update_option('mooc_smtp_username', $_POST['smtp_username']);
    update_option('mooc_smtp_password', $_POST['smtp_password']);
    if ($_POST['send_notify_mail'] == 'on') {
        $t = 'checked';
    } else {
        $t = '';
    }
    update_option('mooc_send_notify_mail', $t);

    update_option('mooc_stat_js', stripslashes($_POST['stat_js']));
}

function themeoptions_admin_menu()
{
    add_theme_page("主题设置", "主题选项", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

add_action('admin_menu', 'themeoptions_admin_menu');


/**
 * 生成文章归档
 *
 * @since ok 1.0.2
 */
function get_archive_by_category($hide_empty = false, $tile_style)
{
    $pages = '<ul id="archives-pages" class="uk-switcher">';
    $output = '<ul class="uk-tab-left" uk-tab="connect: #archives-pages; animation: uk-animation-fade;">';
    $cateargs = array(
        'hide_empty' => $hide_empty
    );
    $categories = get_categories($cateargs);
    foreach ($categories as $category) {
        // 设置 tab 的标签页
        $output .= '<li><a href="#">' . $category->name.'</a>';
        $args = array(
            'category' => $category->term_id,
            'numberposts' => -1
        );
        // 生成对应的 pages 页面
        $pages .= '<li>';
        if($category->description)
        {
            $pages .= '<h3 class="uk-heading-divider">'.$category->description.'</h3>';
        }
        $posts = get_posts($args);
        foreach ($posts as $post) {
            $pages .='<div class="uk-tile uk-tile-'.$tile_style.' uk-padding-small">
            <a class="uk-link-heading" href="' . get_permalink($post->ID) . '"><span class="uk-label uk-margin-small-right">'.human_time_diff(strtotime($post->post_date_gmt), time()).'前</span>&nbsp;' . $post->post_title . '</a>
        </div>';
//            $output .= '<div class="archive-category-post"><a class="archive-category-post-title" href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a><time class="archive-category-post-time">' . human_time_diff(strtotime($post->post_date_gmt), time()) . '前 </time></div>';
        }
        $pages .= '</li>';
        $output .= '</li>';
    }
    $output .= '</ul>';
    $pages .= '</ul>';
    return '<div class="uk-child-width-1-1" uk-grid><div uk-grid><div class="uk-width-auto">'.$output.'</div><div class="uk-width-expand">'.$pages.'</div></div></div>';
}

add_theme_support('automatic-feed-links');

/**
 * 内容链接自动 no-follow
 *
 * @since puu 1.0.0
 */
function content_nofollow($content)
{
    preg_match_all('/href="(.*?)"/', $content, $matches);
    if ($matches) {
        foreach ($matches[1] as $val) {
            if (strpos($val, home_url()) === false)
                $content = str_replace("href=\"$val\"", "href=\"$val\" rel=\"external nofollow\" ", $content);
        }
    }
    return $content;
}

add_filter('the_content', 'content_nofollow', 999);



/**
 * 解决 Gravatar 被墙
 *
 * @since puu 1.0.0
 */
function get_ssl_avatar($avatar)
{
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $avatar);
    return $avatar;
}

add_filter('get_avatar_url', 'get_ssl_avatar');


/**
 * 移除js/css后的wordpress版本号
 *
 * @since puu 1.0.0
 */
function mooc_remove_cssjs_ver($src)
{
    if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}

add_filter('style_loader_src', 'mooc_remove_cssjs_ver', 999);
add_filter('script_loader_src', 'mooc_remove_cssjs_ver', 999);

/**
 * 设置主题的 cookie. light: 亮色主题(默认,light), 暗黑模式(dark)
 */
function set_theme_cookie() {
    if (!isset($_COOKIE['theme'])) {
        setcookie('theme', 'light', time()+1209600, COOKIEPATH, COOKIE_DOMAIN, false);
    }
}

function get_theme_cookie(){
    // 读取
    return $_COOKIE['theme'];
}

/* 添加 body 的字体和背景颜色 */
function write_light_class($classes){
    if(get_theme_cookie() == 'dark'){
        return array_merge($classes, array('uk-light'));
    }
    return $classes;
}
add_filter( 'body_class', 'write_light_class' );

function write_uikit_theme_card(){
    if(get_theme_cookie() == 'dark'){
        echo 'uk-card-secondary';
    }
    else{
        echo 'uk-card-default';
    }
}
function write_uikit_theme_section(){
    if(get_theme_cookie() == 'dark'){
        echo 'uk-section-secondary';
    }
    else{
        echo 'uk-section-muted';
    }
}
function write_uikit_theme_navbar(){
    if(get_theme_cookie() == 'dark'){
        echo 'uk-background-secondary';
    }
    else{
        echo '';
    }
}
function write_uikit_theme_comment_button(){
    if(get_theme_cookie() == 'dark'){
        echo 'uk-button-default';
    }
    else{
        echo 'uk-button-primary';
    }
}


/**
 * 移除不必要的功能
 *
 * @since puu 1.1.0
 */
function remove_unnecessary_funcs()
{
    // wp_deregister_script('jquery');

    // 清理header
    add_filter('pre_option_link_manager_enabled', '__return_true');
    remove_filter('the_content', 'wptexturize');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'locale_stylesheet');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
}

add_action('init', 'remove_unnecessary_funcs');
add_action('init', 'set_theme_cookie');
?>