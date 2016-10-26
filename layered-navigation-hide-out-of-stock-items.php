<?php
/* layered navigation hide out of stock items
** Función para comprobar si los productos listados, con el filtro pa_size tienen stock para la variación indicada 
*/
function product_in_stock($post, $product) {

    if ($_GET['filtering'] == 1 && $_GET['filter_size'] > 0 ) {

        $STOCK = FALSE;
        $slugmap = array();
        $attribs = $product->get_variation_attributes();
        $terms = get_terms( sanitize_title( 'pa_size' ));

        if($terms)foreach($terms as $term)$slugmap[$term->slug]=$term->term_id;
        $available = $product->get_available_variations();
        if($available)foreach($available as $instockitem){

            if(isset($instockitem["attributes"]["attribute_pa_size"])){
				if($instockitem["attributes"]["attribute_pa_size"] == $_GET["filter_size"] && $instockitem["max_qty"]>0){

						$STOCK = TRUE;
				}

            }
        }
        return $STOCK;
    } else {
        return true;
    }
}

// Funciones para crear un div in/out of stock alrededor del producto
function check_if_out_of_stock(){
    global $post,$product;
    $stock = product_in_stock($post,$product);

    $output = '<div class="';
    $output .= $stock?"instock":"outofstock";
    $output .= '">';
	$output .= $stock?"": "<span class='out-stock'>" . __( 'There is no stock for selected size', 'woocommerce' ). "</span>";
    echo $output;
}

add_action( 'woocommerce_before_shop_loop_item', 'check_if_out_of_stock');

function close_out_of_stock(){
    echo "</div>";
}

add_action( 'woocommerce_after_shop_loop_item', 'close_out_of_stock');
