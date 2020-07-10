<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
<input type="hidden" name="post_type" value="post">
<input type="search" class="search-field"
placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'mediumish' ) ?>"
value="<?php echo get_search_query() ?>" name="s"
title="<?php echo esc_attr_x( 'Search for:', 'label', 'mediumish' ) ?>" />
<button type="submit" class="search-submit">
<i class="fa fa-search"></i>   
</button>
</form>