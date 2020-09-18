<?php

/**
 * Funtion To Get Google Fonts
 */
if ( !function_exists( 'gucherry_blog_fonts_url' ) ) :

    /**
     * Return Font's URL.
     *
     * @since 1.0.0
     * @return string Fonts URL.
     */
    function gucherry_blog_fonts_url() {

        $fonts_url = '';
        $fonts     = array();
        $subsets   = 'latin,latin-ext';

        $font_options = array();

        $font_options = gucherry_blog_selected_fonts();

        if( empty( $font_options ) ) {

            /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language.*/
            if ('off' !== _x('on', 'Cormorant Garamond: on or off', 'gucherry-blog')) {

                $font_options[] = 'Cormorant+Garamond:400,400i,500,500i,600,600i,700,700i&display=swap';
            }
            
        }
        
        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language.*/
        if ('off' !== _x('on', 'Poppins font: on or off', 'gucherry-blog')) {

            $font_options[] = 'Poppins:400,400i,500,600,700,700i';
        }

        $font_options = array_unique( $font_options );

        foreach ( $font_options as $f) {

            $f_family = explode(':', $f);

            $f_family = str_replace('+', ' ', $f_family);

            $font_family = ( !empty( $f_family[1]) ) ? $f_family[1] : '';

            $fonts[] = $f_family[0].':'.$font_family;

        }

        if ( $fonts ) {
            $fonts_url = add_query_arg( array(
                'family' => urlencode( implode( '|', $fonts ) ),
                'subset' => urlencode( $subsets ),
            ), '//fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }
endif;

/**
 * Fallback For Main Menu
 */
if ( !function_exists( 'gucherry_blog_navigation_fallback' ) ) {
	/**
     * Return unordered list.
     *
     * @since 1.0.0
     * @return unordered list.
     */
    function gucherry_blog_navigation_fallback() {
        ?>
        <ul class="primary-menu">
            <?php
            if( current_user_can( 'edit_theme_options' ) ) {
                ?>
                <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add Menu', 'gucherry-blog' ); ?></a></li>
                <?php
            } else {
                wp_list_pages( array( 'title_li' => '', 'depth' => 3 ) ); 
            }
            ?>
        </ul>
        <?php
    }
}

/**
 * Customize Readmore Link.
 */
function post_excerpt_more( $more ) {
  	return '...';
}
add_filter( 'excerpt_more', 'post_excerpt_more' );

/**
* Filter the except length to 40 words default.
*/
if( !function_exists( 'gucherry_blog_excerpt_length' ) ) {
   /*
    * Excerpt Length
    */
   function gucherry_blog_excerpt_length( $length ) {
       
       if( is_admin() ) {
           return $length;
       }

       $excerpt_length = get_theme_mod( 'gucherry_blog_excerpt_length', 40 );

       if( absint( $excerpt_length ) > 0 ) {
           $excerpt_length = absint( $excerpt_length );
       }
       return $excerpt_length;
   }
}
add_filter( 'excerpt_length', 'gucherry_blog_excerpt_length' );

/**
 * Generate custom search form
 *
 * @param string $form Form HTML.
 * @return string Modified form HTML.
 */
function gucherry_blog_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url( '/' ) . '" >
    <label>
        <span class="screen-reader-text">' . _x( 'Search for:', 'label', 'gucherry-blog' ) . '</span>
        <input type="search" class="search-field" placeholder="' . esc_attr_x( 'Type Keywords &amp; Hit Enter', 'placeholder', 'gucherry-blog' ) . '" value="' . get_search_query() . '" name="s" />
    </label>
    
    <button type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true">
    </i></button>
    </form>';
 
    return $form;
}
add_filter( 'get_search_form', 'gucherry_blog_search_form' );