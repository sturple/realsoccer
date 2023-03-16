<?php
add_filter( 'should_load_remote_block_patterns', '__return_false' );
add_action( 'init', 'realsoccer_register_patterns' );
add_action( 'init', 'realsoccer_register_blocks' );
add_filter( 'block_categories_all', 'realsoccer_block_categories' );

/**
 * Registers block patterns.
 *
 * @return void
 */
function realsoccer_register_patterns(): void {
    $path = plugin_dir_path( __FILE__ ) . 'patterns/';
    // Register pattern categories.
    $block_pattern_categories = [
        'worldcupsoccercamp' => [ 'label' => __( 'World Cup' ) ],
    ];
    foreach ( $block_pattern_categories as $name => $properties ) {
        register_block_pattern_category( $name, $properties );
    }

    // Register all block patterns found in patterns directory.
    $block_patterns = glob( $path . '*.php' );
    if ( function_exists( 'register_block_pattern' ) ) {
        foreach ( $block_patterns as $block_pattern ) {
            $pattern_name = basename( $block_pattern, '.php' );
            register_block_pattern(
                'worldcupsoccercamp/' . $pattern_name,
                require $block_pattern
            );
        }
    }
}

/**
 * Registers all blocks
 *
 * @return void
 */
function realsoccer_register_blocks(): void {
    $path       = plugin_dir_path( dirname( __FILE__, 1 ) ) . 'build/blocks';
    $categories = realsoccer_get_product_categories();
    register_block_type_from_metadata( $path . '/products', [ 
        'render_callback' =>  'realsoccer_products_block_render',
        'attributes'      => [
            'categories'       => [
                'default' => $categories,
                'type'    => 'array',
            ],
            'category'         => [
                'type'    => 'string',
                'default' => $categories[0]['value']
            ],
        ],
    
    ] );
}


/**
 * The Product dynamic block callback.
 *
 * @param array $attributes  The attributes, from the dynamic block.
 * @return string
 */
function realsoccer_products_block_render( array $attributes ): string {

    $posts = get_posts(
        [
            'post_type'   => 'product',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $attributes['category'] ?? ''
                ]
                
            ],
            'meta_key' => 'start_date',
            'orderby'  => 'meta_value_num',
            'order'    => 'ASC'
        ]
    );
    ob_start();
    foreach ( $posts as $post ) {
        // The variables $post and $data are being passed to the template.
        $price_array = get_post_meta( $post->ID, '_price' );
        $price = array_map( function( $value ){
            return "$" . number_format( $value, 2, '.', ' ' );
        }, get_post_meta( $post->ID, '_price' ) );

        $excerpt = $post->post_excerpt;
        $class_name = implode(' ', 
            array_map( 
                function( $obj ) use ( $post ) {
                    return $obj->slug ? 'is-gender-' . $obj->slug : null;
                }, get_the_terms( $post, 'product_gender')
            )
        );

        $age_terms = array_map( function ( $term ) {
            return sprintf( 
                '<a href="%s">%s</a>',
                get_term_link( $term, 'product_age' ),
                $term->name
            );
        }, get_the_terms( $post->ID, 'product_age' ) );
        $data = (object)[
            "title"  => get_the_title( $post ),
            "content" =>  get_the_content( null, false, $post ),
            "age_terms" =>   implode( ', ', $age_terms),
            "start_date" => get_field( 'start_date', $post->ID ),
            "end_date" => get_field( 'end_date', $post->ID ),
            "price"     => implode( ', ', $price),
            "class_name"     => $class_name, 

        ];
       // logger($data);
        include dirname( __FILE__ ) .'/templates/block/products.php';
        
    }
    
    $output = ob_get_contents();
    ob_end_clean();
    $output = sprintf( '<ul class="product-listing-block">%s</ul>', $output );
    if ( ! empty( $attributes['className'] ) ) {
        $output = sprintf(
            '<div class="%s">%s</div>',
            'wp-block-realsoccer-products ' . $attributes['className'] ?? '', 
            $output 
        );
    }
    return $output;
}

function realsoccer_get_product_categories(): array {
    $terms = get_terms( 'product_cat', [ 'hide_empty' => $attributes['show_all_terms'] ?? false ] );
    $cats = [];
    foreach ( $terms as $term ) {
            
            $cats[] = [
                'value' => $term->slug,
                'label' => $term->name,
            ];
        
    }
    return $cats;
}


function realsoccer_block_categories( array $categories ) :array {
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'realsoccer',
                'title' => __( 'Soccer Blocks' ),
                'icon'  => 'site',
            ],
        ]
    );
}