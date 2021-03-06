<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,minimal-ui"/>
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <?php wp_head();
    global $s, $post, $wp_query;
    $description = '';
    $blog_name = get_bloginfo('name');
    if (is_singular()) {
        $ID = $post->ID;
        $title = $post->post_title;
        $author = $post->post_author;
        $user_info = get_userdata($author);
        $post_author = $user_info->display_name;
        if (!get_post_meta($ID, "meta-description", true)) {
            $description = $title . ' - 作者: ' . $post_author;
        } else {
            $description = get_post_meta($ID, "meta-description", true);
        }
    } elseif (is_home()) {
        $description = '';
    } elseif (is_tag()) {
        $description = single_tag_title('', false) . " - " . trim(strip_tags(tag_description()));
    } elseif (is_category()) {
        $description = single_cat_title('', false) . " - " . trim(strip_tags(category_description()));
    } elseif (is_archive()) {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    } elseif (is_search()) {
        $description = $blog_name . ": '" . esc_html($s, 1) . "' 的搜索结果";
    } else {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    }
    $description = mb_substr($description, 0, 220, 'utf-8');
    echo "<meta name=\"description\" content=\"$description\">\n";
    echo "<link href=\"" . get_template_directory_uri() . "/WordPress-144-144.png\" rel=apple-touch-icon-precomposed>";
    echo get_option('mooc_stat_js');
    ?>
</head>

<body <?php body_class();?>>


<?php if (is_single()): ?>

    <div class="uk-section v-clearfix <?php write_uikit_theme_section();?> uk-background-cover uk-padding-remove-top uk-preserve-color"
         style="background-image: url(<?php if (post_password_required()) echo get_template_directory_uri() . "/assets/banners/default-banner.jpg"; else echo mooc_thumbnail_url(); ?>)">
        <!--stiky 需要指定id！-->
        <div class="uk-visible@m" uk-sticky="animation: uk-animation-slide-top; sel-target: #hm; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent uk-light; top: 200;show-on-up: true">
            <nav class="uk-navbar-container uk-padding uk-padding-remove-bottom uk-padding-remove-top" uk-navbar
                 id="hm">
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo"
                       href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
                    <?php
                    $temp = wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'uk-navbar-nav', 'container' => 'ul', 'echo' => false));
                    echo preg_replace('/[\n]+/i', '', $temp);    // 如果相邻li之间有回车会导致有间隔
                    ?>
                </div>
                <div class="uk-navbar-right">
                    <?php if(get_theme_cookie() == 'dark'): ?>
                        <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到亮色主题" onclick="changeTheme('light')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/sunny.svg);"></span></button>
                    <?php else: ?>
                        <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到暗色主题" onclick="changeTheme('dark')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/moon.svg);"></span></button>
                    <?php endif;?>
                </div>
            </nav>
        </div>
        <!--移动端下滑的导航栏-->
        <div class="uk-hidden@m"
             uk-sticky="animation: uk-animation-slide-top; sel-target: #mb; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent uk-light; top: 200;show-on-up: true">
            <nav class="uk-navbar-container uk-padding uk-padding-remove-bottom uk-padding-remove-top" uk-navbar
                 id="mb">
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo"
                       href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
                </div>
                <div class="uk-navbar-right">
                    <?php if(get_theme_cookie() == 'dark'): ?>
                        <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到亮色主题" onclick="changeTheme('light')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/sunny.svg);"></span></button>
                    <?php else: ?>
                        <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到暗色主题" onclick="changeTheme('dark')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/moon.svg);"></span></button>
                    <?php endif;?>
                    <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon"
                            onclick="executeOffCanvasNavbar()" uk-navbar-toggle-icon=""></button>
                </div>
            </nav>
        </div>
        <div class="uk-section uk-light">
            <div class="uk-container">
                <div class="uk-child-width-1-1 uk-text-center" uk-grid>
                    <div class="article-title-banner">
                        <h1 class="article-title"><?php the_title(); ?></h1>
                        <div class="postMeta"><?php the_author(); ?>发布于<?php echo get_the_date('Y年n月j日'); ?>
                            - <?php echo get_the_category_list(" "); ?>分类
                            - <?php if (function_exists('get_the_views')) echo get_the_views($post->ID); ?>次阅读
                            - <?php echo get_post($post->ID)->comment_count ?>
                            条评论<?php edit_post_link('编辑', ' - '); ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>


<?php else: ?>
<!--一般页面的导航栏-->
    <div class="uk-width-1-1">
        <nav class="uk-navbar-container uk-margin <?php write_uikit_theme_navbar(); ?> uk-padding uk-padding-remove-vertical uk-visible@m" uk-navbar>
            <div class="uk-navbar-left">
                <a class="uk-navbar-item uk-logo"
                   href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
                <?php
                $temp = wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'uk-navbar-nav', 'container' => 'ul', 'echo' => false));
                echo preg_replace('/[\n]+/i', '', $temp);    // 如果相邻li之间有回车会导致有间隔
                ?>
            </div>
            <div class="uk-navbar-right">
                <?php if(get_theme_cookie() == 'dark'): ?>
                    <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到亮色主题" onclick="changeTheme('light')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/sunny.svg);"></span></button>
                <?php else: ?>
                    <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到暗色主题" onclick="changeTheme('dark')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/moon.svg);"></span></button>
                <?php endif;?>
            </div>
        </nav>
        <!-- 单页面的移动端的显示条 -->
        <nav class="uk-navbar-container uk-padding uk-padding-remove-bottom uk-padding-remove-top uk-hidden@m" uk-navbar
             id="mb">
            <div class="uk-navbar-left">
                <a class="uk-navbar-item uk-logo"
                   href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
            </div>
            <div class="uk-navbar-right">
                <?php if(get_theme_cookie() == 'dark'): ?>
                    <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到亮色主题" onclick="changeTheme('light')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/sunny.svg);"></span></button>
                <?php else: ?>
                    <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon" title="切换到暗色主题" onclick="changeTheme('dark')"><span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri() ?>/assets/icons/moon.svg);"></span></button>
                <?php endif;?>
                <button class="uk-button uk-navbar-toggle uk-icon uk-navbar-toggle-icon"
                        onclick="executeOffCanvasNavbar()" uk-navbar-toggle-icon=""></button>
            </div>
        </nav>
        <!-- 移动端的 canvas 均由 header 输出, signle 单独处理 -->
        <!--在移动端显示的导航栏-->
        <div id="offcanvas-non-single-nav" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar" id="offcanvas-nav-bar">
                <ul class="uk-nav uk-nav-default">
                    <li class="uk-nav-header"><a class="uk-logo" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></li>
                    <li class="uk-nav-header"><span class="uk-margin-small-right" uk-icon="icon: menu"></span>页面</li>
                    <li class="uk-nav-divider"></li>
                    <?php
                    $temp = wp_nav_menu(array(
                        'items_wrap' => '%3$s',
                        'container' => '',
                        'depth' => 0,
                        'echo' => false));
                    echo preg_replace('/[\n]+/i', '', $temp);    // 如果相邻li之间有回车会导致有间隔
                    ?>

                </ul>

            </div>
        </div>
    </div>
    </div>

<?php endif; ?>
<script type="text/javascript">
    // var nav = document.getElementsByClassName('uk-navbar-left');
    // for(var i=0;i<nav.length;++i)
    // {
    //     // 修改 margin, 这个问题很奇怪。第一个 页面的margin-top 总是0
    //     if(nav[i].children.length >= 2)
    //     {
    //         var menu = nav[i].children[1];
    //         for(var j=0;j<menu.children.length;++j)
    //         {
    //             menu.children[j].classList.add('uk-margin-remove-top');
    //         }
    //     }
    // }
    function changeTheme(target) {
        // 设置 cookie
        document.cookie  = 'theme=' + target + <?php echo '\';path='.COOKIEPATH.';domain='.COOKIE_DOMAIN.'\''; ?>;
        // 刷新页面
        location.reload();
    }
</script>
