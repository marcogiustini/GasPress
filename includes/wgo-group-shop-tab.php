<?php
function wgo_render_group_shop($group_id) {
    echo '<div class="wgo-group-shop"><h3>Prodotti del gruppo</h3>';
    $args = [
        'post_type' => 'product',
        'meta_query' => [[
            'key' => 'wgo_group_id',
            'value' => $group_id,
            'compare' => '='
        ]]
    ];
    $products = new WP_Query($args);
    if ($products->have_posts()) {
        echo '<ul>';
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            echo '<li>' . get_the_title() . ' - ' . wc_price($product->get_price()) . '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>Nessun prodotto disponibile.</p>';
    }
    echo '</div>';
}
