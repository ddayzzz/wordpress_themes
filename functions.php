<?php
/* 主题通用功能区
---------------------------------------------*/
require( dirname(__FILE__) . '/modules/base.php' );


/* 主题独有功能区
---------------------------------------------*/
add_filter('rest_allow_anonymous_comments', '__return_true');

/**
 * 读取特色图像img标签
 * 
 * @since zhi 1.0.0
*/
function mooc_thumbnail_postflow_url() {
	global $post;
	if ( has_post_thumbnail() ) {
		$domsxe = simplexml_load_string(get_the_post_thumbnail());
		$thumbnailsrc = $domsxe->attributes()->src;
		return $thumbnailsrc;
	} else {
		$content = $post->post_content;
		if( preg_match('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult) > 0 ) {
			return $strResult[1];
		}
		else {
			/*echo '<img width="240" height="160" src="'.get_template_directory_uri().'/default-banner.jpg'.'" alt="'.trim(strip_tags( $post->post_title )).'" />';*/
			return NULL;
		}
	}
}
add_theme_support( 'post-thumbnails' );

/**
 * 获取特色图像url
 * 
 * @since bigsize 1.0.3
*/
function mooc_thumbnail_url() {
	global $post;
	if ( has_post_thumbnail() ) {
		$domsxe = simplexml_load_string(get_the_post_thumbnail());
		return $domsxe->attributes()->src;
	} else {
		$content = $post->post_content;
		if( preg_match('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult) > 0 ) {
			return $strResult[1];
		}
		else {
			return get_template_directory_uri().'/assets/banners/default-banner.jpg';
		}
	}
}

/**
 * 添加底部小工具
 * 
 * @since ok 1.0.0
*/
function mooc_footerwidgets_init() {
	register_sidebar( array(
	'id' => 'site-footer',
	'name' => __('Footer', 'huge'),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
	));
}
add_action( 'widgets_init', 'mooc_footerwidgets_init' );
function mooc_print_styles(){
	// 自动调整底部小工具宽度
	$sidebars_widgets = wp_get_sidebars_widgets();
	$count = isset($sidebars_widgets['site-footer']) ? count($sidebars_widgets['site-footer']) : 1;
	$count = max($count,1);
	// 自动调整单双栏
	$usesidebar1 = count($sidebars_widgets['sidebar-1']);
	$usesidebar2 = count($sidebars_widgets['sidebar-2']);
	?>
	<style type="text/css" media="screen">
	#footer-widgets .widget { width: <?php echo round(100/$count,3) . '%' ?>; }
	<?php 
	if(is_singular()){ 
		if(!$usesidebar2){echo '.posts { width: 100%; }';}
	}elseif(!$usesidebar1){
		echo '.posts { width: 100%; }';
	}?>
	@media screen and (max-width: 640px) {
		#footer-widgets .widget { width: auto; float: none; }
	}
	</style>
	<?php
}
add_action('wp_head', 'mooc_print_styles', 11);



?>