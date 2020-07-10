<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">


<?php wp_head(); ?>
<?php echo get_theme_mod( 'head_sectiontracking'); ?>
</head>

<body <?php body_class(); ?>>

<?php
global $post;
global $post_ids;

$mediumish_headertwitterlink = get_theme_mod( 'mediumish_headertwitterlink');
$headersociallinks = get_theme_mod( 'mediumish_headersociallink' );
$mediumish_headersearchlink = get_theme_mod( 'mediumish_headersearch_active' );
$disableauthorbox = get_theme_mod( 'disable_authorbox_sectionarticles_card');
$disablereadingtime = get_theme_mod( 'disable_readingtime_sectionarticles_card');
$disabledate = get_theme_mod( 'disable_date_sectionarticles_card');
$disabledot = get_theme_mod( 'disable_dot_sectionarticles_card');
?>
<style>
    <?php
    if ($disableauthorbox == 1) { ?> .author-thumb, span.post-name {display:none;} <?php }
    if ($disablereadingtime == 1) { ?> span.readingtime {display:none;} <?php }
    if ($disabledate == 1) { ?> span.post-date {display:none;} <?php }
    if ($disabledot == 1) { ?> span.author-meta span.dot {display:none;} <?php }
    ?>
</style>


<header class="navbar-light bg-white fixed-top mediumnavigation">

    <div class="container">


        <div class="navarea">

        <nav class="navbar navbar-toggleable-sm">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#bs4navbar" aria-controls="bs4navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <?php
                   wp_nav_menu([
                     'menu'            => 'primary',
                     'theme_location'  => 'primary',
                     'container'       => 'div',
                     'container_id'    => 'bs4navbar',
                     'container_class' => 'collapse navbar-collapse',
                     'menu_id'         => false,
                     'menu_class'      => 'navbar-nav col-md-12 justify-content-center',
                     'depth'           => 2,
                     'fallback_cb'     => 'bs4navwalker::fallback',
                     'walker'          => new bs4navwalker()
                   ]);
                   ?>
        </nav>

        </div>

    </div>

</header>


        <!-- Begin site-content
		================================================== -->
        <div class="site-content">
