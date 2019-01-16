<?php
//IDENTIFICA SE JA COMPROU O PRODUTO E MUDA O BOTAO (COLOCA ISSO NO SEU FUNCTION)
function has_bought_items($meuCursoID) {
    $bought = false;

    // Set HERE ine the array your specific target product IDs
    $prod_arr = array( $meuCursoID );

    // Get all customer orders
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => 'shop_order', // WC orders post type
        'post_status' => 'wc-completed' // Only orders with status "completed"
    ) );
    foreach ( $customer_orders as $customer_order ) {
        // Updated compatibility with WooCommerce 3+
        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
        $order = wc_get_order( $customer_order );
        //print_r($order);
        // Iterating through each current customer products bought in the order
        foreach ($order->get_items() as $item) {
            // WC 3+ compatibility
            if ( version_compare( WC_VERSION, '3.0', '<' ) ) 
                $product_id = $item['product_id'];
            else
                $product_id = $item->get_product_id();

            // Your condition related to your 2 specific products Ids
            if ( in_array( $product_id, $prod_arr ) ) 
                $bought = true;
        }
    }
    // return "true" if one the specifics products have been bought before by customer
    return $bought;
} 
?>

<?php //COLOCA ISSO NO SEU LOOP OU NO SINGLE. OBS: VOCE PODE USAR NORMALMENTE OS CAMPOS PERSONALIZADOS ?>
<?php if(has_bought_items($product->get_id())): ?>
  <span>Produto Adquirido</span>
<?php else : ?>
  <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">comprar</button>
<?php endif; ?>

