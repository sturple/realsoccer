<div class="wp-block-realsoccer-products">
<ul>
<?php foreach ( $posts as $post ): ?>
    <?php
    $acf_fields = get_fields($post->ID);
    $price = get_post_meta($post->ID, '_price' );
    ?>
    <li>
        <h2><?php echo $post->post_title; ?></h2>
        <div><?php echo wpautop( $post->post_content); ?></div>
        <div>$<?php echo implode(',',$price); ?></div>
        
        <?php if ( $acf_fields ): ?>
            <?php foreach ( $acf_fields as $name => $value ): ?>
                <div><b><?php echo $name; ?></b> <?php echo is_array($value) ? implode(', ',$value) : $value ; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a class="btn btn-primary" style="padding: 1rem 2rem; border: 1px solid;display:inline-block" href="<?php the_permalink( $post->ID) ; ?>">Register</a>
    </li> 
<?php endforeach; ?>
</ul>
</div>