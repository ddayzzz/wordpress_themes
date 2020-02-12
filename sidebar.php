<div class="sidebar">
    <?php if(is_singular()){
        dynamic_sidebar( 'sidebar-2' );
    }else{
        dynamic_sidebar( 'sidebar-1' );
    }?>
</div>
<script type="text/javascript">
    // 这里没办法, 使用 DOM 来控制 siderbar
    jQuery("div.sidebar ul").addClass("uk-list");
</script>