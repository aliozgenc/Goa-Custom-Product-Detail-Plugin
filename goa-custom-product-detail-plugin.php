<?php
/*
Plugin Name: Custom Product Field with Images and Text
Description: WooCommerce ürün sayfalarına özel bir alan ekler ve her seçenek için bir resim ve metin yükler.
Version: 1.0.1
Author: Ali Ozgenc
*/

// Ürün düzenleme sayfasına alanı eklemek için fonksiyon
function add_custom_product_field() {
    global $woocommerce, $post;

    // Sadece ürün sayfasında göster
    if (get_post_type($post) === 'product') {
        echo '<div class="options_group">';

        // Önceden belirlenmiş seçenekler, resim URL'leri ve metinler
        $options = array(
            'Seçenek 1' => array(
                'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
                'text'  => 'Wall',
            ),
            'Seçenek 2' => array(
                'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
                'text'  => 'Kitchen',
            ),
            'Seçenek 3' => array(
                'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
                'text'  => 'Floor',
            ),
        );

        foreach ($options as $option => $data) {
            woocommerce_wp_checkbox(array(
                'id'      => '_custom_field_' . sanitize_title($option),
                'label'   => $option,
                'value'   => get_post_meta($post->ID, '_custom_field_' . sanitize_title($option), true),
            ));

            echo '<p><img src="' . esc_url($data['image']) . '" alt="' . esc_attr($option) . '" style="max-width: 100px; max-height: 100px;"></p>';
            echo '<p>' . esc_html($data['text']) . '</p>';
        }

        echo '</div>';
    }
}

// Ürün düzenleme sayfasına ekstra alanı eklemek için kancayı tanımla
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_field');

// Kullanıcının seçtiği değeri kaydetmek için fonksiyon
function save_custom_product_field($post_id) {
    // Sadece ürünler için kaydet
    if (get_post_type($post_id) === 'product') {
        // Önceden belirlenmiş seçenekler
        $options = array(
            'Seçenek 1' => array(
                'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
                'text'  => 'Wall',
            ),
            'Seçenek 2' => array(
                'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
                'text'  => 'Kitchen',
            ),
            'Seçenek 3' => array(
                'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
                'text'  => 'Floor',
            ),
        );

        foreach ($options as $option => $data) {
            // Eğer değer set edilmişse, kaydet
            if (isset($_POST['_custom_field_' . sanitize_title($option)])) {
                update_post_meta($post_id, '_custom_field_' . sanitize_title($option), sanitize_text_field($_POST['_custom_field_' . sanitize_title($option)]));
            } else {
                // Kullanıcı hiçbir şey seçmediyse, alanı temizle
                delete_post_meta($post_id, '_custom_field_' . sanitize_title($option));
            }
        }
    }
}

// Ürün kaydedilirken ekstra alan değerini kaydetmek için kancayı tanımla
add_action('save_post', 'save_custom_product_field');

// Ürün sayfasında ön yüzde alanı göstermek için fonksiyon
function display_custom_product_field() {
    global $product;

    echo '<div class="custom-product-field">';
    

    // Önceden belirlenmiş seçenekler, resim URL'leri ve metinler
    $options = array(
        'Seçenek 1' => array(
            'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
            'text'  => 'Wall',
        ),
        'Seçenek 2' => array(
            'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
            'text'  => 'Kitchen',
        ),
        'Seçenek 3' => array(
            'image' => 'https://countryclassic.co.uk/wp-content/uploads/2023/11/Adsiz_tasarim__34_-removebg-preview.png',
            'text'  => 'Floor',
        ),
    );

    foreach ($options as $option => $data) {
        // Seçenek seçiliyse göster
        if (get_post_meta($product->get_id(), '_custom_field_' . sanitize_title($option), true) === 'yes') {
            
            echo '<p><img src="' . esc_url($data['image']) . '" alt="' . esc_attr($option) . '" style="max-width: 80px; max-height: 80px;"></p>';
            echo '<p>' . esc_html($data['text']) . '</p>';
        }
    }

    echo '</div>';
}

// Ürün sayfasına ekstra alanı eklemek için kancayı tanımla (ön yüzde)
add_action('woocommerce_before_add_to_cart_form', 'display_custom_product_field');
