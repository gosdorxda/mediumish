<?php
/**
 * Single
 */
get_header(); 
$disableauthorbox = get_theme_mod( 'disable_authorbox_sectionarticles'); 
$disablereadingtime = get_theme_mod( 'disable_readingtime_sectionarticles'); 
$disableshare = get_theme_mod( 'disable_share_sectionarticles');
$disabledate = get_theme_mod( 'disable_date_sectionarticles'); 
$disablerp = get_theme_mod( 'disable_rp_sectionarticles'); 
$disablecats = get_theme_mod( 'disable_cats_sectionarticles'); 
$disabletags = get_theme_mod( 'disable_tags_sectionarticles'); 
$disablebottomalert = get_theme_mod( 'disable_bottomalert_sectionarticles');
$mediumishbottomalert = get_theme_mod( 'mediumish_bottomalert');
?>


<div class="container"> 
    <div class="row">         
        <div class="<?php if ( is_active_sidebar( 'sidebar-posts' ) ) { ?> col-md-1 <?php } else { ?> col-md-2 <?php } ?>">           
            <div class="share">
                
                <div class="sidebarapplause">
                <?php
                    if( class_exists( 'WPClapsApplause' ) ) {
                    echo do_shortcode('[wp_claps_applause ]'); 
                    }
                ?>
                </div>
                
                <?php if ($disableshare == 0) { ?>
				<p class="sharecolour">
					<?php _e( 'Share', 'mediumish' ); ?>
				</p>
                <?php mediumish_share_post(); ?>
                <?php } ?>
		        
                
                <?php if ( comments_open() || get_comments_number() ) : ?>
                <div class="sep"></div>
                <div class="hidden-xs-down">      

                    <p>
                        <?php _e( 'Reply', 'mediumish' ); ?>
                    </p>
                    <ul>
                        <li>
                        <a class="smoothscroll" href="#comments"><?php printf( _nx( '1', '%1$s', get_comments_number(), 'comments title', 'mediumish' ), number_format_i18n( get_comments_number() ), get_the_title() ); ?><br/>
                        <svg class="svgIcon-use" width="29" height="29" viewBox="0 0 29 29"><path d="M21.27 20.058c1.89-1.826 2.754-4.17 2.754-6.674C24.024 8.21 19.67 4 14.1 4 8.53 4 4 8.21 4 13.384c0 5.175 4.53 9.385 10.1 9.385 1.007 0 2-.14 2.95-.41.285.25.592.49.918.7 1.306.87 2.716 1.31 4.19 1.31.276-.01.494-.14.6-.36a.625.625 0 0 0-.052-.65c-.61-.84-1.042-1.71-1.282-2.58a5.417 5.417 0 0 1-.154-.75zm-3.85 1.324l-.083-.28-.388.12a9.72 9.72 0 0 1-2.85.424c-4.96 0-8.99-3.706-8.99-8.262 0-4.556 4.03-8.263 8.99-8.263 4.95 0 8.77 3.71 8.77 8.27 0 2.25-.75 4.35-2.5 5.92l-.24.21v.32c0 .07 0 .19.02.37.03.29.1.6.19.92.19.7.49 1.4.89 2.08-.93-.14-1.83-.49-2.67-1.06-.34-.22-.88-.48-1.16-.74z"></path></svg>
                        </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>			                                
        </div>
        
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <div <?php post_class( 'col-md-8  flex-first flex-md-unordered' ); ?> id="post-<?php the_ID(); ?>"> 
                    <div class="mainheading">
                       
                        <?php if ($disableauthorbox == 0) { ?>
                        <!-- Begin Author box -->                                     
                        <div class="row post-top-meta hidden-md-down"> 
                            <div class="col-md-2 col-xs-12"> 
                                <a href="<?php echo get_author_posts_url($post->post_author); ?>">
                                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), '72', null, null, array( 'class' => array( 'imgavt' ) ) ); ?>
                                </a>
                            </div>                                         
                            <div class="col-md-10 col-xs-12"> 
                                <a class="text-capitalize link-dark" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
                                <?php the_author(); ?> <span class="btn follow"><?php _e( 'Follow', 'mediumish' ); ?></span></a>
                                <span class="author-description d-block"><?php the_author_meta( 'description' ); ?></span> 
                            </div>                                         
                        </div>
                         <?php } ?>
                        
                        <?php
                        if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('
                        <p id="breadcrumbs">','</p>
                        ');
                        }
                        ?>
                        
                        <h1 class="posttitle"><?php the_title(); ?></h1>
                        <p>
                            <?php if ($disabledate == 0) { ?>
                            <span class="post-date"><time class="post-date">
                            <?php echo get_the_date(); ?>
                            </time></span>
                            <?php } ?>
                            
                            <?php if ($disablereadingtime == 0) { ?>
                            <span class="dot"></span>
                            <span class="readingtime"><?php echo mediumish_estimated_reading_time() ?></span>
                            <?php } ?>
                        </p> 
                    </div>
                    
                  <?php          
                    $hide_featimg =  get_post_meta( get_the_ID(), "hide_featured_image_hide_featured_image_on_post", true );
                    if(!$hide_featimg) {
                        if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'full', array(
                                'class' => 'featured-image img-fluid'
                            ) );
                        }
                    }
                    ?>
                    <article class="article-post">
                        <?php wtn_ad_block_top_article (); ?>
                        <?php the_content(); ?>
                        <div class="clearfix"></div>
                        <?php wtn_ad_block_bottom_article (); ?>
                        <div class="clearfix"></div>
                    </article>
                            
                     <?php if ($disableauthorbox == 0) { ?>
                        <!-- Begin Author box -->                                     
                        <div class="row post-top-meta hidden-lg-up"> 
                            <div class="col-md-2 col-xs-4"> 
                                <?php echo get_avatar( get_the_author_meta('email'), '72', get_the_author() ); ?>
                            </div>                                         
                            <div class="col-md-10 col-xs-8"> 
                                <a class="text-capitalize link-dark" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
                                <?php the_author(); ?> <span class="btn follow"><?php _e( 'Follow', 'mediumish' ); ?></span></a>
                                <span class="author-description d-block"><?php the_author_meta( 'description' ); ?></span> 
                            </div>                                         
                        </div>
                     <?php } ?>
                    
                    <div class="after-post-tags">
                        <?php if ($disablecats == 0) { ?>
                            <?php the_category();?>
                        <?php } ?>
                        <?php if ($disabletags == 0) { ?>
                            <ul class="post-categories aretags">
                            <?php 
                                if (is_array(get_the_tags($post->ID)) || is_object(get_the_tags($post->ID))) :
                                    foreach(get_the_tags($post->ID) as $tag)
                                    {
                                        echo '<li><a href="' . get_tag_link($tag->term_id) . '">#' . $tag->name . '</a></li> ';
                                    }
                                endif;
                            ?>
                            </ul>
                        <?php } ?>
                    </div>
                    
                  
                    <div class="row mb-5 prevnextlinks justify-content-center align-items-center"> 
                       <div class="col-md-6 col-xs-12 rightborder pl-0"><div class="thepostlink"><?php previous_post_link(); ?></div></div>
                       <div class="col-md-6 col-xs-12 text-right pr-0"><div class="thepostlink"><?php next_post_link(); ?></div></div>
                    </div>
                    
                </div>
        
            <?php endwhile; ?>
        
        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.', 'mediumish' ); ?></p>
        <?php endif; ?> 
        
        <?php if ( is_active_sidebar( 'sidebar-posts' ) ) : ?>
            <div class="col-md-3">
                <?php get_sidebar();?>
            </div>
        <?php endif; ?>

    </div>                 
</div>             
          
<div class="hideshare"></div>

<?php if ($disablerp == 0) { ?>
    <div class="graybg"> 
        <div class="container"> 
            <?php echo mediumish_related_posts() ?>
            <?php 
                if ( comments_open() || get_comments_number() ) : 
                comments_template();
                endif; 
            ?>
        </div>                 
    </div>
<?php } ?>

<?php if ($disablebottomalert == 0) { ?>
    <div class="alertbar"> 
        <div class="container text-center"> 
            <?php echo do_shortcode ($mediumishbottomalert); ?>
        </div>
    </div>
<?php } ?>

<?php get_footer(); ?>