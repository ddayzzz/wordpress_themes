<?php get_header(); ?>
<div class="uk-container v-clearfix uk-width-1-1">
    <div class="uk-grid" data-ukgrid="">
        <div class="uk-width-1-1 uk-width-3-4@m">
            <div class="uk-margin uk-card <?php write_uikit_theme_card() ?> uk-card-body uk-card-hover">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="article-content">
                        <?php
                        $s = get_the_content();
                        if ((strncmp($s, '<img ', 5) == 0) or (strncmp($s, '<p><img ', 8) == 0) or (strncmp($s, "<!-- wp:image ", 14) == 0))
                            print preg_replace('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', '', apply_filters('the_content', $s), 1);
                        else
                            print apply_filters('the_content', $s);
                        ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php if(comments_open()): ?>
                <div class="uk-margin uk-card <?php write_uikit_theme_card() ?> uk-card-body uk-card-hover" id="article-comment-box">
                    <?php comments_template(); ?>
                </div>
            <?php endif;?>
        </div>
        <div class="uk-width-1-4@m uk-visible@m uk-height-medium">
            <div class=" uk-card uk-card-secondary uk-card-body uk-card-hover uk-sticky uk-sticky-fixed article-head-container" uk-sticky="offset: 100" >
                <ul class="uk-nav-default uk-nav-parent-icon" id="article-head-category" uk-nav="multiple: true">
                </ul>
            </div>
        </div>

    </div>
</div>
<div id="gotop"></div>
<?php get_footer(); ?>

<script type="text/javascript">
    var category = null;
    var rootEle = null;

    function executeOffCanvasNavbar() {
        UIkit.offcanvas("#offcanvas-non-single-nav").show();
    }

    function addHeadItem(level, text, id) {
        var link = document.createElement("a");
        link.href = "#" + id;
        link.appendChild(document.createTextNode(text));

        var item = document.createElement("li");
        if (level === 0 || (level === 1 && rootEle == null /*h2 且不在 h1 之下*/)) {
            item.className = "uk-parent";
            item.appendChild(link);
            var sub = document.createElement("ul");
            sub.className = "uk-nav-sub";
            sub.style.marginLeft = '0';
            item.appendChild(sub);
            if (rootEle != null && !rootEle.firstChild.nextSibling.hasChildNodes()) {
                // 删除空的子节点, rootEle 下面是是 a 和 ul
                rootEle.classList.remove('uk-parent');
                rootEle.removeChild(rootEle.lastChild);
            }
            rootEle = item;

            category.appendChild(rootEle);
            // mobileCategory.appendChild(rootEle)
        } else {
            item.style.paddingLeft = level * 25 + "px";
            item.appendChild(link);
            var treeRoot = rootEle.getElementsByClassName("uk-nav-sub")[0];
            treeRoot.appendChild(item);
        }

    }

    function genCategory() {
        var article = document.getElementsByClassName("article-content");
        if (article.length == 0)
            return;
        else
            article = article[0];

        category = document.getElementById("article-head-category");

        for (var i = article.firstChild; i != article.lastChild; i = i.nextSibling) {
            switch (i.tagName) {
                case "H1":
                    if (i.id == "")
                        i.id = i.innerText;
                    addHeadItem(0, i.innerText, i.id);
                    break;
                case "H2":
                    if (i.id == "")
                        i.id = i.innerText;
                    addHeadItem(1, i.innerText, i.id);
                    break;
                case "H3":
                    if (i.id == "")
                        i.id = i.innerText;
                    addHeadItem(2, i.innerText, i.id);
                    break;
                case "H4":
                    if (i.id == "")
                        i.id = i.innerText;
                    addHeadItem(3, i.innerText, i.id);
                    break;
                case "H5":
                    if (i.id == "")
                        i.id = i.innerText;
                    addHeadItem(4, i.innerText, i.id);
                    break;
                case "H6":
                    if (i.id == "")
                        i.id = i.innerText;
                    addHeadItem(5, i.innerText, i.id);
                    break;
            }
        }
    }

    genCategory();
    // 滑动目录
    // 移除可能的参考标签之下没有列表
    if (rootEle != null && !rootEle.firstChild.nextSibling.hasChildNodes()) {
        // 删除空的子节点, rootEle 下面是是 a 和 ul
        rootEle.classList.remove('uk-parent');
        rootEle.removeChild(rootEle.lastChild);
    }
    categoryList = document.getElementById('article-head-category');
    if(categoryList.childElementCount <= 0)
    {
        categoryList.parentNode.style.display = 'none';
    }

    jQuery(".article-content ul").addClass("uk-list uk-list-bullet");
    jQuery(".article-content img").wrap(function () {
        return "<div uk-lightbox class='uk-text-center'><a class=\"uk-inline\" href=" + jQuery(this).attr('src') + "></a></div>";
    });
</script>
