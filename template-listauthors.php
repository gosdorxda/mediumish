<?php
/*
Template Name: Display Authors
*/
get_header(); ?>

<div class="container">
    
<div class="section-title"> 
    <h2>
        <span><?php the_title(); ?></span>
    </h2> 
</div>
<?php
    while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>                    
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
<?php endwhile; ?>
    
<div class="row listrecent listrecent listauthor">
       
<?php 
    $display_admins = true;
    $order_by = 'post_count'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
    $order = 'DESC';
    $roleadmin = get_users( array( 'role' => 'administrator' ) );
    $roleauthor = get_users( array( 'role' => 'author' ) );
    $rolecontributor = get_users( array( 'role' => 'contributor' ) );
    
    $role = array_merge($roleauthor, $roleadmin, $rolecontributor);
     
     $avatar_size = 90;
     $hide_empty = true; // hides authors with zero posts 
     $blogusers = get_users($role);
     $authors = array();
    
     foreach ($blogusers as $bloguser) {
     $user = get_userdata($bloguser->ID); 
     if(!empty($hide_empty)) {
          $numposts = count_user_posts($user->ID);
          if($numposts < 1) continue;
          }
          $authors[] = (array) $user;
     }
 
     
     foreach($authors as $author) {
        $display_name = $author['data']->display_name;
        $email = $author['data']->user_email;
        $description = get_user_meta($author['ID'], 'description', true);
        $twitter = get_user_meta($author['ID'], 'twitter', true);
        $facebook = get_user_meta($author['ID'], 'facebook', true);
        $youtube = get_user_meta($author['ID'], 'youtube', true);
        $website = $author['data']->user_url;
        $avatar = get_avatar($author['ID'], $avatar_size);
        $author_profile_url = get_author_posts_url($author['ID']);
        $user_post_count = count_user_posts( $author['ID'] , $post_type = 'post' );
        ?>
 
        <div class="col-lg-4 col-md-4">
            <div class="card post author text-center mt-5 mb-2">
                <div class="card-block">
                    <a href="<?php echo $author_profile_url; ?>"><?php echo $avatar; ?></a>
                    <h2 class="card-title"><a href="<?php echo $author_profile_url; ?>" class="contributor-link"><?php echo $display_name; ?><br/>
                    <small><?php echo $user_post_count.' '.esc_attr__('posts', 'mediumish'); ?></small></a></h2>
                    <span class="card-text d-block"><?php echo $description; ?></span>
                    <span class="card-text d-block mt-1"><a target="_blank" href="<?php echo esc_url($website); ?>"><?php echo esc_url($website); ?></a></span>
                    <div class="profile-icons mt-3">
                    <a target="_blank" href="<?php echo $author_profile_url; ?>feed"><i class="fa fa-rss"></i></a> &nbsp 
                    <a href="mailto:<?php echo $email; ?>"><i class="fa fa-envelope"></i></a> &nbsp;
                    <?php if ($twitter) { ?>
                    <a target="_blank" href="<?php echo $twitter; ?>"><i class="fa fa-twitter"></i></a> &nbsp;
                    <?php } ?>
                    <?php if ($facebook) { ?>
                    <a target="_blank" href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i></a> &nbsp;
                    <?php } ?>
                    <?php if ($youtube) { ?>
                    <a target="_blank" href="<?php echo $youtube; ?>"><i class="fa fa-youtube"></i></a>  &nbsp;
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>

<?php } ?>

</div>
</div>

<?php get_footer(); ?>