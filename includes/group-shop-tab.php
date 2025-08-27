<?php
/**
 * Tab "Shop" nel gruppo BuddyPress: mostra prodotti collegati al gruppo
 */

function wgo_render_group_shop($group_id) {
    echo '<div class="wgo-group-shop">';
    echo '<h3>' . __('Prodotti del gruppo', 'wgo') . '</h3>';

    // 🔍 Filtro per categoria
    $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
    echo '<form method="get" class="wgo-filter-form">';
    echo '<select name="wgo_cat_filter">';
    echo '<option value="">' . __('Tutte le categorie', 'wgo') . '</option>';
    foreach ($terms as $term) {
        $selected = isset($_GET['wgo_cat_filter']) && $_GET['wgo_cat_filter'] == $term->term_id ? 'selected' : '';
        echo '<option value="' . $term->term_id . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="' . __('Filtra', 'wgo') . '" class="button">';
    echo '</form>';

    // 📦 Query prodotti
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => [[
            'key' => 'wgo_group_id',
            'value' => $group_id,
            'compare' => '='
        ]]
    ];

    if (!empty($_GET['wgo_cat_filter'])) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => intval($_GET['wgo_cat_filter'])
        ]];
    }

    $products = new WP_Query($args);

    if ($products->have_posts()) {
        echo '<ul class="wgo-product-list">';
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            $vendor_id = get_post_field('post_author', get_the_ID());
            $vendor_name = get_userdata($vendor_id)->display_name;
            $vendor_url = function_exists('dokan_get_store_url') ? dokan_get_store_url($vendor_id) : '#';

            echo '<li class="wgo-product">';
            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            echo '<span class="wgo-badge">' . __('Solo per questo gruppo', 'wgo') . '</span>';
            echo '<span class="wgo-vendor">' . __('Venditore:', 'wgo') . ' <a href="' . esc_url($vendor_url) . '">' . esc_html($vendor_name) . '</a></span>';
            echo '<span class="wgo-price">' . wc_price($product->get_price()) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>' . __('Nessun prodotto disponibile per questo gruppo.', 'wgo') . '</p>';
    }

    echo '</div>';
}
