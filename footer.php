
<div class="uk-container uk-width-1-1 uk-padding-remove-left uk-padding-remove-right" id="footer-widgets">
    <div class="uk-section uk-section-small uk-section-primary uk-flex-center uk-flex uk-text-center">
        <div class="footer">
            <div class="section">
                <p>Powered by WordPress and UIkit, Theme <a href="https://ddayzzz.wang/theme" target="_blank">naïve</a>, based on <a href="https://moooc.cc/theme" target="_blank">ok</a>&nbsp;and&nbsp;<a href="https://moooc.cc/theme" target="_blank">bigsize</a>.</p>
                <p><?php echo get_option('mooc_copyright')?></p>
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