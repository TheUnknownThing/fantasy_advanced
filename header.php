<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta http-equiv="Cache-Control" content="no-transform"/>
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta content="telephone=no" name="format-detection"/>
<meta name="renderer" content="webkit">
<?php $this->header('generator=&template=&pingback=&xmlrpc=&commentReply='); ?>
<title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
<link data-n-head="true" rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css'); ?>">
<script type="text/javascript" src="<?php $this->options->themeUrl('css/jquery.js'); ?>"></script>
<?php if ($this->is('post') && $this->fields->isLatex == 1): ?>
<script defer type="text/javascript" src="https://cdn.staticfile.net/KaTeX/0.16.9/katex.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.staticfile.net/KaTeX/0.16.9/katex.min.css" />
<script defer type="text/javascript" src="https://cdn.staticfile.net/KaTeX/0.16.9/contrib/auto-render.min.js"></script>
<?php endif; ?>
<?php if ($this->options->fontshow == 'able'): ?>
<style type="text/css">* {text-shadow : 0.01em 0.01em 0.01em #999999}</style>
<?php endif; ?>
<script>
        (function() {
            function getTheme() {
                const savedTheme = localStorage.getItem("theme");
                if (savedTheme) {
                    return savedTheme;
                }
                return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            
            const theme = getTheme();
            document.documentElement.setAttribute("data-theme", theme);
        })();
    </script>
</head>
<body>
<div class="wrapper">
	<header>
		<?php if ($this->options->displayLogo == 'able'): ?>
				<a href="<?php $this->options->siteUrl();?>" class="logo">
					<?php if($this->options->logoUrl): ?><img src="<?php $this->options->logoUrl();?>" alt="<?php $this->options->title() ?>" />
					<?php else : ?><img src="<?php $this->options->themeUrl('css/pic.png'); ?>"/>
					<?php endif; ?>
				</a>
		<?php endif; ?>
	<div class="description">
		<h1><?php $this->options->title(); ?></h1>
		<h2><?php $this->options->description() ?></h2>
		<nav>
		<div class="bitcron_nav_container">
			<div class="bitcron_nav">
				<div class="mixed_site_nav_wrap site_nav_wrap">
					<ul class="mixed_site_nav site_nav sm sm-base">
						<li><a  href="<?php $this->options->siteUrl();?>" class="selected active current nav__item">首页</a></li>
						<?php $this->widget('Widget_Contents_Page_List')->parse('<li><a href="{permalink}" class="nav__item">{title}</a></li>'); ?>
						<li><a href="<?php $this->options->feedUrl(); ?>" class=" nav__item">订阅</a></li>
						<li>
						  <button id="darkModeToggle" class="dark-mode-toggle nav__item">
							<span id="darkModeIcon">💻</span>
						  </button>
						</li>
					</ul>
					<div class="clear clear_nav_inline_end">
					</div>
				</div>
			</div>
			<div class="clear clear_nav_end">
			</div>
		</div>
		</nav>
	</div>
	</header>
