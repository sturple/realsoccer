<?php
add_action( 'admin_notices', function () {
    echo '<div class="notice notice-success">Blocks</div>';
});

add_action( 'init', 'realsoccer_register_blocks' );

function realsoccer_register_blocks(): void {
    $path = plugin_dir_path( dirname( __FILE__, 1 ) ) . 'build/blocks';
    register_block_type_from_metadata( $path . '/products', [ 
        'render_callback' =>  'realsoccer_products_block_render',
        'attributes'      => [
            'categories'       => [
                'default' => realsoccer_get_product_categories(),
                'type'    => 'array',
            ],
            'category'         => [
                'type' => 'string',
            ],
        ],
    
    ] );
}



function realsoccer_products_block_render( array $attributes ): string {
    
    $category = $attributes['category'] ?? [];
    error_log(print_R($category,true));
    $posts = get_posts(
        [
            'post_type'   => 'product',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $category
                ]
                
            ]
        ]
    );
    ob_start();
    include dirname( __FILE__ ) .'/templates/block/products.php';
    $output = ob_get_contents();
    ob_end_clean();
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

add_filter( 'block_categories_all', 'realsoccer_block_categories' );
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