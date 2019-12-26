
<?php echo '<h4 class="uk-heading-line uk-text-bold"><span>搜索</span></h4>';?>
<form>
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: search"></span>
            <input class="uk-input" type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
        </div>
</form>