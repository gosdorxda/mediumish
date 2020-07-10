<?php
/**
 * Search Results
 */
get_header(); ?>

<div class="container">
    
    <section class="recent-posts"> 
        <div class="section-title"> 
            <h2><?php echo 'Search results for: <span>'.get_query_var('s').'</span>';?></h2> 
        </div>
        
        <?php if ( have_posts() ) : ?>
        
         <div class="masonrygrid row listrecent">                
            <!-- begin post -->                             
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-lg-4 col-md-4 col-sm-6 grid-item">
                    <?php echo mediumish_postbox(); ?>
                </div>            
            <?php endwhile; ?> 
            <!-- end post -->                
        </div>

        <!-- pagination -->                     
        <div class="bottompagination mt-4">
            <?php wp_bootstrap_pagination( array(
                    'previous_string' => '<i class="fa fa-angle-double-left"></i>',
                    'next_string' => '<i class="fa fa-angle-double-right"></i>',
                    'before_output' => '<span class="navigation">',
                    'after_output' => '</span>'
            ) ); ?> 
        </div> 


        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.', 'mediumish' ); ?></p>
        <?php endif; ?> 
        
                    
        
    </section>
    
</div>
<!-- /.container -->            

<?php get_footer(); ?>