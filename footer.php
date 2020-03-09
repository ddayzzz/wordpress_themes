
<div class="uk-container uk-width-1-1 uk-padding-remove-left uk-padding-remove-right uk-margin-small-top" id="footer-widgets">
    <div class="uk-section uk-section-small uk-flex-center uk-flex uk-text-center <?php write_uikit_theme_section(); ?>">
        <div class="footer">
            <div class="section">
                <p>Powered by WordPress and UIkit, Theme <a href="https://ddayzzz.wang/theme" target="_blank">naïve</a>, based on <a href="https://moooc.cc/theme" target="_blank">ok</a>&nbsp;and&nbsp;<a href="https://moooc.cc/theme" target="_blank">bigsize</a>.</p>
                <!-- 设置备案信息 -->
                <p><a href="http://www.beian.miit.gov.cn" target="_blank"><?php echo get_option('shu_icp_beian') ?></a>
                    <?php if(strlen(get_option('shu_gongan_beian')) > 0):?>
                    &nbsp;|&nbsp;<span class="uk-icon uk-icon-image" style="background-image: url(<?php echo get_template_directory_uri().'/assets/icons/gongan_beian.svg' ?>);"></span>
                        <a href="http://www.beian.gov.cn" target="_blank">
                        <?php echo get_option('shu_gongan_beian') ?>
                    </a>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

</div>
<!-- </div> 去掉 sites  class -->
<script src="https://cdn.bootcss.com/highlight.js/9.15.6/highlight.min.js"></script>
<script type="text/x-mathjax-config">  
MathJax.Hub.Config({
  TeX: {equationNumbers: { autoNumber: "AMS" } },
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<?php wp_footer(); ?>
</body>
</html>