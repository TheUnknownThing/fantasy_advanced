<?php
/**
 * 时光机
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/timeline.css'); ?>">
<main class="col-mb-12 col-8" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
    </article>
    <div id="timeline">
        <?php
        $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);
        $year = 0;
        $mon = 0;
        $i = 0;
        $j = 0;
        while ($archives->next()):
            $year_tmp = date('Y', $archives->created);
            $mon_tmp = date('m', $archives->created);
            $y = $year;
            $m = $mon;
            if ($year != $year_tmp || $mon != $mon_tmp) {
                $year = $year_tmp;
                $mon = $mon_tmp;
                if ($j > 0) {
                    echo '</ul></li>';
                }
                echo '<h3>'. $year . ' 年 ' . $mon . ' 月</h3><ul class="timeline-list">';
                $j = 1;
            }
            $i++;
            $time = date('d日', $archives->created);
            echo '<li><span class="date">'.$time.'</span><a href="'.$archives->permalink .'">'. $archives->title .'</a></li>';
        endwhile;
        echo '</ul>';
        ?>
    </div>
</main>

<?php $this->need('footer.php'); ?>