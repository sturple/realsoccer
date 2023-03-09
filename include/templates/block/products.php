
<?php
$acf_fields = get_fields($post->ID);

$price = get_post_meta($post->ID, '_price' );
?>
<li class="<?php echo $data->class_name ; ?>">
    <h2><?php echo $data->title; ?></h2>
    <div style="font-size: 1.3rem"><strong><?php echo $data->price; ?></strong></div>
    
    <div><?php echo ( $data->content); ?></div>
    

    <?php if ( $acf_fields && false): ?>
        <?php foreach ( $acf_fields as $name => $value ): ?>
            <div><b><?php echo $name; ?></b> <?php echo is_array($value) ? implode(', ',$value) : $value ; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <a class="btn btn-register"  href="<?php the_permalink( $post->ID) ; ?>">Register</a>
</li> 

