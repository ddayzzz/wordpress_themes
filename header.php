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

<body <?php body_class(); ?> >


<?php if (is_single()): ?>

    <div class="uk-section v-clearfix uk-section-primary uk-background-cover uk-padding-remove-top uk-preserve-color"
         style="background-image: url(<?php if (post_password_required()) echo get_template_directory_uri() . "/assets/banners/default-banner.jpg"; else echo mooc_thumbnail_url(); ?>)">
        <!--stiky 需要指定id！-->
        <div class="uk-visible@l" uk-sticky="animation: uk-animation-slide-top; sel-target: #hm; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent uk-light; top: 200">
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
            </nav>
        </div>
        <!--移动端下滑的导航栏-->
        <div class="uk-hidden@l"
             uk-sticky="animation: uk-animation-slide-top; sel-target: #mb; cls-active: uk-navbar-sticky; cls-inactive: uk-navbar-transparent uk-light; top: 200">
            <nav class="uk-navbar-container uk-padding uk-padding-remove-bottom uk-padding-remove-top" uk-navbar
                 id="mb">
                <div class="uk-navbar-left">
                    <a class="uk-navbar-item uk-logo"
                       href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
                </div>
                <div class="uk-navbar-right">
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
    <div class="uk-width-1-1">
        <nav class="uk-navbar-container uk-margin" uk-navbar>
            <div class="uk-navbar-left uk-padding uk-padding-remove-vertical uk-padding-remove-right">
                <a class="uk-navbar-item uk-logo"
                   href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
                <?php
                $temp = wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'uk-navbar-nav', 'container' => 'ul', 'echo' => false));
                echo preg_replace('/[\n]+/i', '', $temp);    // 如果相邻li之间有回车会导致有间隔
                ?>
            </div>
        </nav>
    </div>

<?php endif; ?>
<script type="text/javascript">
    var nav = document.getElementsByClassName('uk-navbar-left');
    for(var i=0;i<nav.length;++i)
    {
        // 修改 margin, 这个问题很奇怪。第一个 页面的margin-top 总是0
        if(nav[i].children.length >= 2)
        {
            var menu = nav[i].children[1];
            for(var j=0;j<menu.children.length;++j)
            {
                menu.children[j].classList.add('uk-margin-remove-top');
            }
        }
    }
</script>
