<?php

//-----------------------------------------------------
// Setup
//-----------------------------------------------------
if ( ! function_exists( 'mediumish_setup' ) ) :

function mediumish_setup() {
    if ( ! isset( $content_width ) ) {
		$content_width = 730; /* pixels */
	}
    load_theme_textdomain( 'mediumish', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    set_post_thumbnail_size( 825, 510, true );
    
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'mediumish' ),
    ) );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
}
endif;
add_action( 'after_setup_theme', 'mediumish_setup' );




//-----------------------------------------------------
// Scripts & Styles
//-----------------------------------------------------
if ( ! function_exists( 'mediumish_enqueue_scripts' ) ) :
function mediumish_enqueue_scripts() {        
    wp_enqueue_script( 'tether', 'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'bootstrap4', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'mediumish-ieviewportbugworkaround', get_template_directory_uri() . '/assets/js/ie10-viewport-bug-workaround.js', array('jquery'), null, true );
    wp_enqueue_script( 'mediumish-masonrypkgd', get_template_directory_uri() . '/assets/js/masonry.pkgd.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'mediumish', get_template_directory_uri() . '/assets/js/mediumish.js', array('jquery'), null, true );

    wp_enqueue_style( 'bootstrap4', get_template_directory_uri() . '/assets/css/bootstrap.min.css', false, null, 'all');
    wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, null, 'all');
    wp_enqueue_style( 'mediumish-style', get_stylesheet_uri() );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'mediumish_enqueue_scripts' );
endif;

//----------------------------------------------------
// Register Widgets
//-----------------------------------------------------
if ( ! function_exists( 'mediumish_sidebar_widgets_init' ) ) :
function _widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar WooCommerce Shop', 'mediumish' ),
		'id'            => 'sidebar-woocommerce',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title"><span>',
		'after_title'   => '</span></h4>',
	) );
    register_sidebar( array(
		'name'          => __( 'Sidebar Posts', 'mediumish' ),
		'id'            => 'sidebar-posts',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title"><span>',
		'after_title'   => '</span></h4>',
	) );
}
endif; // _widgets_init
add_action( 'widgets_init', '_widgets_init' );

//-----------------------------------------------------
// Excerpt
//-----------------------------------------------------
function excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'...';
	} else {
	$excerpt = implode(" ",$excerpt);
	} 
	$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	return $excerpt;
	}

	function content($limit) {
	$content = explode(' ', get_the_content(), $limit);
	if (count($content)>=$limit) {
	array_pop($content);
	$content = implode(" ",$content).'...';
	} else {
	$content = implode(" ",$content);
	} 
	$content = preg_replace('/\[.+\]/','', $content);
	$content = apply_filters('the_content', $content); 
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

//-----------------------------------------------------
// Reading Time
//-----------------------------------------------------
function mediumish_estimated_reading_time() {
    $post = get_post();
    $words = str_word_count( strip_tags( $post->post_content ) );
    $minutes = floor( $words / 280 );
    $seconds = floor( $words % 280 / ( 280 / 60 ) );
   
    if ( 1 <= $minutes ) {
        $estimated_time = $minutes . ' ' .esc_attr__('min read', 'mediumish');
    } else {
        $estimated_time = $seconds . ' ' .esc_attr__('sec read', 'mediumish');
    }   
    return $estimated_time;
}

//-----------------------------------------------------
// Limit title characters
//-----------------------------------------------------
function limit_word_count( $title ) {
    $limitcharacterstitle = get_theme_mod('mediumish_limitcharacterstitle'); 
    if ($limitcharacterstitle) { 
        $len = $limitcharacterstitle; 
    } else {
        $len = 9; 
    }
    return wp_trim_words( $title, $len, '&hellip;' );
}

//-----------------------------------------------------
// Share
//-----------------------------------------------------
if (! function_exists('mediumish_share_post')) {
function mediumish_share_post() { 
global $post;
$shareURL = urlencode(get_permalink());
$shareTitle = str_replace( ' ', '%20', get_the_title());
$shareThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
$twitterURL = 'https://twitter.com/intent/tweet?text='.$shareTitle.'&amp;url='.$shareURL;
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shareURL;
$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$shareURL.'&amp;media='.$shareThumbnail[0].'&amp;description='.$shareTitle; 
$linkedinURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$shareURL.'&amp;title='.$shareTitle; 
$googleURL = 'https://plus.google.com/share?url='.$shareURL;
$disablesharetwitter = get_theme_mod( 'disable_share_twitter');
$disablesharefb = get_theme_mod( 'disable_share_fb'); 
$disablesharepinterest = get_theme_mod( 'disable_share_pinterest');
$disablesharelinkedin = get_theme_mod( 'disable_share_linkedin');
$disablesharegoogle = get_theme_mod( 'disable_share_google'); 
    
echo '
<ul class="shareitnow">';
     if ($disablesharetwitter == 0) { echo 
        '<li>
        <a target="_blank" href="'.$twitterURL.'">
        <i class="fa fa-twitter"></i>
        </a>
    </li>'; }
                                   
    if ($disablesharefb == 0) { echo 
        '<li>
        <a target="_blank" href="'.$facebookURL.'">        
        <i class="fa fa-facebook"></i>
        </a>
    </li>'; }
    
    if ($disablesharegoogle == 0) { echo 
        '<li>
        <a target="_blank" href="'.$googleURL.'">
        <i class="fa fa-google"></i>
        </a>
    </li>'; }
                                     
    if ($disablesharepinterest == 0) { echo 
        '<li>
        <a target="_blank" href="'.$pinterestURL.'">
        <i class="fa fa-pinterest"></i>
        </a>
    </li>'; }
                                            
    if ($disablesharelinkedin == 0) { echo 
        '<li>
        <a target="_blank" href="'.$linkedinURL.'">
        <i class="fa fa-linkedin"></i>
        </a>
    </li>'; }
    
echo '</ul>';
   
    }
}

//-----------------------------------------------------
// Hide applause button plugin, it's already in theme
//-----------------------------------------------------
add_filter( 'wpli/autoadd', function() {return false;} );

//-----------------------------------------------------
// Meta Tag
//-----------------------------------------------------
function mediumish_custom_get_meta_excerpt() {
    global $post;
    $temp = $post;
    $post = get_post();
    setup_postdata( $post );
    $excerpt = esc_attr(strip_tags(get_the_excerpt()));    
    wp_reset_postdata();
    $post = $temp;
    return $excerpt;
}

//-----------------------------------------------------
// Comment Form
//-----------------------------------------------------
function my_update_comment_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$label     = $req ? '*' : ' ' . __( '(optional)', 'mediumish' );
	$aria_req  = $req ? "aria-required='true'" : '';
	$fields['author'] =
		'<div class="row"><p class="comment-form-author col-md-4">
			
			<input id="author" name="author" type="text" placeholder="' . esc_attr__( "Name", "mediumish" ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
		'" size="30" ' . $aria_req . ' />
		</p>';
	$fields['email'] =
		'<p class="comment-form-email col-md-4">
			
			<input id="email" name="email" type="email" placeholder="' . esc_attr__( "E-mail address", "mediumish" ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
		'" size="30" ' . $aria_req . ' />
		</p>';
	$fields['url'] =
		'<p class="comment-form-url col-md-4">
			
			<input id="url" name="url" type="url"  placeholder="' . esc_attr__( "Website Link", "mediumish" ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
		'" size="30" />
			</p></div>';
	return $fields;
}
add_filter( 'comment_form_default_fields', 'my_update_comment_fields' );

function my_update_comment_field( $comment_field ) {
  $comment_field =
    '<p class="comment-form-comment">            
            <textarea required id="comment" name="comment" placeholder="' . esc_attr__( "Write a response...", "mediumish" ) . '" cols="45" rows="8" aria-required="true"></textarea>
        </p>';
  return $comment_field;
}
add_filter( 'comment_form_field_comment', 'my_update_comment_field' );


//-----------------------------------------------------
// Postbox
//-----------------------------------------------------
function mediumish_postbox() {
    add_filter( 'the_title', 'limit_word_count');
    global $post;  
    $featured_img_url =  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>

    <?php if ($featured_img_url) { echo '<div class="card post highlighted"><a class="thumbimage" href="' . get_permalink() . '" style="background-image:url('.$featured_img_url.');"></a>'; } else { echo '<div class="card post height262">';}
    
    echo '<div class="card-block">
    <h2 class="card-title"><a href="' . get_permalink() . '">' . substr( get_the_title(), 0, 200 ) . '</a></h2>
    <span class="card-text d-block">'.excerpt(25).'</span>
    <div class="metafooter"> 
    <div class="wrapfooter">
    <span class="meta-footer-thumb"> 
    <a href="'.get_author_posts_url($post->post_author).'">
    '.get_avatar( get_the_author_meta( 'user_email' ), '40', null, null, array( 'class' => array( 'author-thumb' ) ) ).'
    </a>
    </span>    
    <span class="author-meta"> 
        <span class="post-name"><a href="'.get_author_posts_url($post->post_author).'">'.get_the_author_meta( 'display_name').'</a></span><br> 
        <span class="post-date">'.get_the_date('M j, Y').'</span>
        <span class="dot"></span>
        <span class="readingtime">'. mediumish_estimated_reading_time().'</span> 
    </span> 
    <span class="post-read-more">
    <a href="' . get_permalink() . '" title="Read Story">
    <svg class="svgIcon-use" width="25" height="25" viewBox="0 0 25 25">
        <path d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z" fill-rule="evenodd"></path>
    </svg>
    </a>
    </span> 
    </div>                                             
    </div>
    </div>
    </div>
    ';
}


//-----------------------------------------------------
// Author Postbox (from list all authors)
//-----------------------------------------------------
function mediumish_authorpostbox() {
    global $post;   
    $featured_img_url =  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
    echo'
    <div class="card post authorpost">';
    if ( $featured_img_url != '' ): echo '
    <a class="thumbimage" href="' . get_permalink() . '" style="background-image:url('.$featured_img_url.');"></a>';
    endif; 
    echo '
    <div class="card-block">
    <h2 class="card-title"><a href="' . get_permalink() . '">' . substr( get_the_title(), 0, 200 ) . '</a></h2>
    <span class="card-text d-block">'.excerpt(25).'</span>
    <div class="metafooter"> 
    <div class="wrapfooter"> 
    <span class="author-meta"> 
        <span class="post-date">'.get_the_date().'</span>
        <span class="dot"></span>';
    ?>
    <?php if ( comments_open() ) { echo '
        <span class="muted"><i class="fa fa-comments"></i> '.get_comments_number().'</span>
        <span class="dot"></span>'; } ?>
    <?php echo '<span class="readingtime">'. mediumish_estimated_reading_time().'</span> 
    </span> 
    <span class="post-read-more">
    <a href="' . get_permalink() . '">
    <svg class="svgIcon-use" width="25" height="25" viewBox="0 0 25 25">
        <path d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z" fill-rule="evenodd"></path>
    </svg>
    </a>
    </span> 
    </div>                                             
    </div>
    </div>
    </div>
    ';
}


//-----------------------------------------------------
// Post Card Highlight First in Row
//-----------------------------------------------------
function mediumish_post_card_highlight_first () { 
    global $post;
    $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');?>
    <div class="card post <?php if ( $featured_img_url) { echo 'highlighted'; } else { echo 'height262'; }?>">
            <?php if ( $featured_img_url != '' ): echo '
            <a class="thumbimage" href="' . get_permalink() . '" style="background-image:url('.$featured_img_url.');"></a>';
            endif; 
            ?>
            <div class="card-block">
            <h2 class="card-title"><a href="<?php echo esc_url( the_permalink() ); ?>"><?php the_title (); ?></a></h2>
            <span class="card-text d-block"><?php echo excerpt(29); ?></span>
            <div class="metafooter"> 
                <?php echo '<div class="wrapfooter"> 
                <span class="meta-footer-thumb"> 
                <a href="'.get_author_posts_url($post->post_author).'">
                '.get_avatar( get_the_author_meta( 'user_email' ), '40', null, null, array( 'class' => array( 'author-thumb' ) ) ).'
                </span>
                </a>
                <span class="author-meta"> 
                    <span class="post-name"><a href="'.get_author_posts_url($post->post_author).'">'.get_the_author_meta( 'display_name').'</a></span><br> 
                    <span class="post-date">'.get_the_date('M j, Y').'</span>
                    <span class="dot"></span>
                    <span class="readingtime">'. mediumish_estimated_reading_time().'</span> 
                </span> 
                <span class="post-read-more">
                <a href="' . get_permalink() . '" title="Read Story">
                <svg class="svgIcon-use" width="25" height="25" viewBox="0 0 25 25">
                    <path d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z" fill-rule="evenodd"></path>
                </svg>
                </a>
                </span> 
                </div>'; ?>
            </div>
            </div>
     </div>
<?php } 

//-----------------------------------------------------
// Post Card After Highlight First in Row
//-----------------------------------------------------
function mediumish_post_card_after_highlight () { 
global $post; 
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
?>

<div class="card post height262"> 
<?php 
if ( $featured_img_url != '' ) {  
echo '<a class="thumbimage" href="' . get_permalink() . '" style="background-image:url('.$featured_img_url.');"></a>';
} ?>
<div class="card-block">
    <h2 class="card-title">
        <a href="<?php echo get_permalink(); ?>"><?php echo substr( get_the_title(), 0, 200 ) ; ?></a>
    </h2>
    <?php if ( $featured_img_url == '' ) { echo '<span class="card-text d-block">'.excerpt(25).'</span>'; } ?>
        <div class="metafooter">                        
            <div class="wrapfooter"> 
                <span class="meta-footer-thumb"> 
                    <a href="<?php echo get_author_posts_url($post->post_author); ?>">
                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), '40', null, null, array( 'class' => array( 'author-thumb' ) ) ); ?>
                    </a>
                </span>                                
                <?php echo '<span class="author-meta"> 
                    <span class="post-name">
                    <a href="'.get_author_posts_url($post->post_author).'">'.get_the_author_meta( 'display_name').'</a></span><br> 
                    <span class="post-date">'.get_the_date('M j, Y').'</span>
                    <span class="dot"></span>
                    <span class="readingtime">'. mediumish_estimated_reading_time().'</span> 
                </span> 
                <span class="post-read-more">
                    <a href="' . get_permalink() . '" title="">
                    <svg class="svgIcon-use" width="25" height="25" viewBox="0 0 25 25">
                        <path d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z" fill-rule="evenodd"></path>
                    </svg>
                    </a>
                </span>';?>
            </div>                        
        </div>
    </div>
</div>
<?php } 

//-----------------------------------------------------
// Post Card Tall
//-----------------------------------------------------
function mediumish_post_card_tall () { 
global $post; 
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
?>
<div class="card" id="post-<?php echo the_ID(); ?>">
    <div class="row">
        <?php if ($featured_img_url) { ?>
        <div class="col-md-5 wrapthumbnail">
            <a href="<?php echo get_permalink(); ?>">
                <div class="thumbnail" style="background-image:url(<?php echo esc_url($featured_img_url);?>);">
                </div>
            </a>        
        </div>
        <?php } ?>
        <div class="<?php if ($featured_img_url) { ?> col-md-7 <?php } else { ?> nothumbimage <?php } ?>">
            <div class="card-block">
                <h2 class="card-title"><a href="<?php echo get_permalink(); ?>"><?php echo substr( get_the_title(), 0, 200 ) ; ?></a></h2>
                <span class="card-text d-block"><?php echo excerpt(20); ?></span>
                <div class="metafooter">
                    <div class="wrapfooter"> 
                        <span class="meta-footer-thumb"> 
                            <a href="<?php echo get_author_posts_url($post->post_author); ?>">
                            <?php echo get_avatar( get_the_author_meta( 'user_email' ), '40', null, null, array( 'class' => array( 'author-thumb' ) ) ); ?>
                            </a>
                        </span>                                
                        <?php echo '<span class="author-meta"> 
                            <span class="post-name">
                            <a href="'.get_author_posts_url($post->post_author).'">'.get_the_author_meta( 'display_name').'</a></span><br> 
                            <span class="post-date">'.get_the_date('M j, Y').'</span>
                            <span class="dot"></span>
                            <span class="readingtime">'. mediumish_estimated_reading_time().'</span> 
                        </span> 
                        <span class="post-read-more">
                            <a href="' . get_permalink() . '" title="">
                            <svg class="svgIcon-use" width="25" height="25" viewBox="0 0 25 25">
                                <path d="M19 6c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v14.66h.012c.01.103.045.204.12.285a.5.5 0 0 0 .706.03L12.5 16.85l5.662 4.126a.508.508 0 0 0 .708-.03.5.5 0 0 0 .118-.285H19V6zm-6.838 9.97L7 19.636V6c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v13.637l-5.162-3.668a.49.49 0 0 0-.676 0z" fill-rule="evenodd"></path>
                            </svg>
                            </a>
                        </span>';?>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div> 
<?php } 




//-----------------------------------------------------
// Related Posts
//-----------------------------------------------------
function mediumish_related_posts($args = array()) {
    global $post;
    add_filter( 'the_title', 'limit_word_count');

    // default args
    $args = wp_parse_args($args, array(
        'post_id' => !empty($post) ? $post->ID : '',
        'taxonomy' => 'category',
        'limit' => 3,
        'post_type' => !empty($post) ? $post->post_type : 'post',
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    // check taxonomy
    if (!taxonomy_exists($args['taxonomy'])) {
        return;
    }

    // post taxonomies
    $taxonomies = wp_get_post_terms($args['post_id'], $args['taxonomy'], array('fields' => 'ids'));

    if (empty($taxonomies)) {
        return;
    }

    // query
    $related_posts = get_posts(array(
        'post__not_in' => (array) $args['post_id'],
        'post_type' => $args['post_type'],
        'limit' => 3,
        'tax_query' => array(
            array(
                'taxonomy' => $args['taxonomy'],
                'field' => 'term_id',
                'terms' => $taxonomies
            ),
        ),
        'posts_per_page' => $args['limit'],
        'orderby' => $args['orderby'],
        'order' => $args['order']
    ));

    if (!empty($related_posts)) {  ?>
    <div class="row justify-content-center listrecent listrelated">      
        <?php
        foreach ($related_posts as $post) { setup_postdata($post); ?>
        <div class="col-lg-4 col-md-4 col-sm-4"> 
            <?php echo mediumish_post_card_after_highlight(); ?>                                     
        </div>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
    <?php
    }

    wp_reset_postdata();
}

//-----------------------------------------------------
// Return an alternate title, without prefix, for every type used in the get_the_archive_title().
//-----------------------------------------------------
function mediumish_archive_title() {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_year() ) {
        $title = get_the_date(  'Y', 'yearly archives date format'  );
    } elseif ( is_month() ) {
        $title = get_the_date( 'F Y', 'monthly archives date format' );
    } elseif ( is_day() ) {
        $title = get_the_date( 'F j, Y', 'daily archives date format' );
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    } else {
        $title = _( 'Posts', 'mediumish' );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'mediumish_archive_title' );


//-----------------------------------------------------
// Add social fields to user profile
//-----------------------------------------------------
if ( ! function_exists( 'mediumish_user_fields' ) ) :

    function mediumish_user_fields( $contactmethods ) {
        $contactmethods['twitter'] = 'Twitter';
        $contactmethods['facebook'] = 'Facebook';
        $contactmethods['youtube'] = 'YouTube';
        $contactmethods['location'] = 'Location';

        return $contactmethods;
    }
    add_filter('user_contactmethods','mediumish_user_fields', 10, 1);

endif;


//-----------------------------------------------------
// Ad Blocks
//-----------------------------------------------------
if ( ! function_exists( 'wtn_ad_block_top_article' ) ) :
	function wtn_ad_block_top_article() {
			$toparticle = get_theme_mod('toparticle_sectionad');
			if (!empty($toparticle) ) {
			echo '<div class="wtntopadarticle"><p>' . get_theme_mod( 'toparticle_sectionad') .'</p></div>';
			}
	}
endif;

if ( ! function_exists( 'wtn_ad_block_bottom_article' ) ) :
	function wtn_ad_block_bottom_article() {
		$bottomarticle = get_theme_mod('bottomarticle_sectionad');
		if (!empty($bottomarticle) ) {
	  echo '<div class="wtnbottomadarticle"><p>' . get_theme_mod( 'bottomarticle_sectionad') .'</p></div>';
	  }
 }
endif;


//-----------------------------------------------------
// Hide Featured Image from post
//-----------------------------------------------------

function hide_featured_image_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function hide_featured_image_add_meta_box() {
	add_meta_box(
		'hide_featured_image-hide-featured-image',
		__( 'Hide Featured Image', 'hide_featured_image' ),
		'hide_featured_image_html',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'hide_featured_image_add_meta_box' );

function hide_featured_image_html( $post) {
	wp_nonce_field( '_hide_featured_image_nonce', 'hide_featured_image_nonce' ); ?>

	<p>

		<input type="checkbox" name="hide_featured_image_hide_featured_image_on_post" id="hide_featured_image_hide_featured_image_on_post" value="hide-featured-image-on-post" <?php echo ( hide_featured_image_get_meta( 'hide_featured_image_hide_featured_image_on_post' ) === 'hide-featured-image-on-post' ) ? 'checked' : ''; ?>>
		<label for="hide_featured_image_hide_featured_image_on_post"><?php _e( 'Hide featured image on post', 'hide_featured_image' ); ?></label>	</p><?php
}

function hide_featured_image_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['hide_featured_image_nonce'] ) || ! wp_verify_nonce( $_POST['hide_featured_image_nonce'], '_hide_featured_image_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['hide_featured_image_hide_featured_image_on_post'] ) )
		update_post_meta( $post_id, 'hide_featured_image_hide_featured_image_on_post', esc_attr( $_POST['hide_featured_image_hide_featured_image_on_post'] ) );
	else
		update_post_meta( $post_id, 'hide_featured_image_hide_featured_image_on_post', null );
}
add_action( 'save_post', 'hide_featured_image_save' );

/*
	Usage: hide_featured_image_get_meta( 'hide_featured_image_hide_featured_image_on_post' )
*/



//-----------------------------------------------------
// Require
//-----------------------------------------------------
require_once get_template_directory() . '/inc/bootstrap/wp_bootstrap_pagination.php';
require_once get_template_directory() . '/inc/bootstrap/wp_bootstrap_navwalker.php';
require_once get_template_directory() . '/inc/include-kirki.php';
require_once get_template_directory() . '/inc/kirki-fallback.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

//-----------------------------------------------------
// TGMPA
//-----------------------------------------------------
if (! function_exists('mediumish_required_plugins')) {
    function mediumish_required_plugins() {
        $config = array(
            'id'           => 'mediumish',
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => ''
        );
        $plugins = array(
            array(
                'name'     => esc_html__('Kirki', 'mediumish'),
                'slug'     => 'kirki',
                'required' => true,
            ),
            array(
                'name'     => esc_html__('MailChimp for WordPress', 'mediumish'),
                'slug'     => 'mailchimp-for-wp',
                'required' => false,
            ),
             array(
                'name'     => esc_html__('WP Frontend Submit', 'mediumish'),
                'slug'     => 'wp-frontend-submit',
                'required' => false,
            ),
            array(
                'name'     => esc_html__('Contact Form 7', 'mediumish'),
                'slug'     => 'contact-form-7',
                'required' => false,
            ),
            array(
                'name'     => esc_html__('Wow Popup', 'mediumish'),
                'slug'     => 'wowpopup',
                'source'   => 'https://s3.amazonaws.com/wtnplugins/wowpopup.zip',
                'required' => false,
            ),
            array(
                'name'     => esc_html__('WP Applause Button', 'mediumish'),
                'slug'     => 'wp-claps-applause',
                'source'   => 'https://s3.amazonaws.com/wtnplugins/wp-claps-applause.zip',
                'required' => false,
            ),

        );
        tgmpa($plugins, $config);
    }
    add_action('tgmpa_register', 'mediumish_required_plugins');
}


?>