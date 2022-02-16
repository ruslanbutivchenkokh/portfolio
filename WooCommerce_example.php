<?php 

/**
 * CATALOG PRODUCTS
 */
add_action('woocommerce_before_main_content', 'show_sliders_in_categories', 30);

add_action('woocommerce_before_shop_loop', 'product_loop_start_html', 40);

add_action('woocommerce_after_shop_loop', 'product_loop_end_html', 40);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'catalog_product_image', 5);

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'catalog_product_title', 10);

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
add_action('woocommerce_before_shop_loop_item', 'catalog_product_link', 10);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);

add_action('woocommerce_before_end_shop_loop', 'woocommerce_taxonomy_archive_description', 15);
add_action('woocommerce_before_end_shop_loop', 'woocommerce_product_archive_description', 15);

add_action('woocommerce_after_main_content', 'sales_products', 10);
add_action('woocommerce_after_main_content', 'subscribe_form', 20);

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/* SINGLE PRODUCTS */
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);


add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5);
add_action('woocommerce_single_product_summary', 'add_to_cart_in_mobile', 5);

/**
* simple product price
*/
add_action('woocommerce_after_add_to_cart_button', 'woocommerce_template_single_price', 20);

/**
* variation product price
*/
remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10);
add_action('woocommerce_after_add_to_cart_button', 'woocommerce_single_variation', 20);

add_action( 'woocommerce_after_single_product_summary' , 'show_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product', 'woocommerce_upsell_display', 15);

add_action('woocommerce_after_cart', 'add_button_to_chechout_visible', 10);

add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment_variations', 20 );

add_action('woocommerce_checkout_after_customer_details', 'show_delivery', 10);

add_action('woocommerce_after_add_to_cart_quantity', 'show_stocks', 10);

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);

add_action('woocommerce_before_end_shop_loop', 'pagination_custom', 10);

add_action('woocommerce_checkout_before_order_review', 'woocommerce_custom_coupon_input', 10);

add_action('woocommerce_before_shop_loop', 'custom_h1_tag', 10);

function custom_h1_tag() {
    $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    if(!empty($current_term) && is_archive()) {
        echo '<h1 class="term_h1">' . get_field('title_h1', 'term_'.$current_term->term_id) . '</h1>';
    }
}

function add_to_cart_in_mobile() {
?>
    <div class="btn_def btn_def_4 close-option" id="add_to_cart_in_mobile">
            <span class="icon">
                <svg>
                    <use xlink:href="#close_x"></use>
                </svg>
            </span>
        </div>
<?php
}

function woocommerce_custom_coupon_input() {
    ?>
    <p class="form-row form-row-first">
        <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_custom_code" value="" />
        <a class="btn_def btn_def_4" id="submit_coupon"><span class="icon"><svg><use xlink:href="#icon-arrow-right"></use></svg></span></a>
    </p>
    <?php  
}

function pagination_custom() {
    $args = array(
        'show_all'     => false,
        'end_size'     => 1,
        'mid_size'     => 3,
        'prev_next'    => true,
        'prev_text'    => '<img src="' . get_template_directory_uri() . '/assets/images/svg/arrow-left.svg">',
        'next_text'    => '<img src="' . get_template_directory_uri() . '/assets/images/svg/arrow-right.svg">',
        'add_args'     => false,
        'add_fragment' => '',
        'screen_reader_text' => '',
    );
    the_posts_pagination($args);
}

function show_sliders_in_categories() {

	$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
	if( have_rows('slider', 'term_'.$current_term->term_id) ): ?>
		<section class="main main_catalog">
	        <div class="wrap_main">
	            <div class="swiper-container swiper-top-main">
	                <div class="swiper-wrapper">

					    <?php while ( have_rows('slider', 'term_'.$current_term->term_id) ) : the_row(); ?>

		                    <div class="swiper-slide">
		                        <div class="row_custom w100 jcsb">
		                            <div class="box_text">
		                                <div class="title_def"><?php the_sub_field('title'); ?></div>
		                                <p><?php the_sub_field('text'); ?></p>
		                                <a href="<?php echo get_sub_field('button')['url']; ?>" class="btn_def_1 btn_def">
		                                    <span class="text"><?php echo get_sub_field('button')['title']; ?></span>
		                                    <span class="icon">
		                                        <svg>
		                                            <use xlink:href="#icon-arrow-right"></use>
		                                        </svg>
		                                    </span>
		                                </a>
		                            </div>
		                            <div class="box_images">
		                                <div class="col_img"><img src="<?php echo wp_get_attachment_image_url(get_sub_field('image'), 'full'); ?>" alt=""> </div> 
		                            </div>
		                        </div>
		                    </div>

					    <?php endwhile; ?>
    				</div>
	            </div>
	            <div class="box_control box_control_def">
	                <div class="swiper-pagination-top swiper-pagination-def"></div>
	                <div class="arrov_box arrov_def">
	                    <div class="swiper-button-prev-slide swiper-button-prev-slide-def">
	                        <svg class="icon">
	                            <use xlink:href="#Arrow-left-slide"></use>
	                        </svg>
	                    </div>
	                    <div class="swiper-button-next-slide swiper-button-next-slide-def">
	                        <svg class="icon">
	                            <use xlink:href="#Arrow-slide-right"></use>
	                        </svg>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </section>
	<?php
	endif;
}

function product_loop_start_html() {
?>
	<section class="section_product">
        <div class="wrap_product">
            <div class="row_custom jcsb">
                <div class="btn_filter_wrap">
                    <div class="title_filter"><?php echo 'Product filter'; ?></div>
                    <div class="btn_filter">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/filter.svg">
                    </div>
                </div>
        		<div class="col_filter" id="filter_column_category">

                    <div class="btn_check">
                    	<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/check.svg">
                    </div>


                    <div class="title_def_second"><?php echo 'Product filter'; ?></div>
                     <?php
                    if ( is_active_sidebar( 'filter' ) ) :
                        dynamic_sidebar( 'filter' );
                    endif;
                    ?>
                </div>
<?php
}

function product_loop_end_html() {
?>
			</div>
		</div>
	</section>
<?php
}

function catalog_product_image() {
?>
    <a href="<?php the_permalink(); ?>">
        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
    </a>
<?php
}

function catalog_product_title() {
    echo '<a href="' . get_the_permalink() . '">';
	the_title();
    echo "</a>";
}

function catalog_product_link() {
?>
	<a href="<?php the_permalink(); ?>" class="btn_def btn_def_3">
        <span><?php echo 'More details'; ?></span>
        <svg class="icon">
            <use xlink:href="#Arrow-slide-right"></use>
        </svg>
    </a>
<?php
}

function sales_products() {
    $products = get_field('sales_products', pll_current_language('slug'));
    if(!empty($products)) : ?>
    	<section class="new_slide slide_def slide_not_pagination">
            <div class="top_wrapper row_custom jcsb">
                <div class="title_def"><?php echo 'Promotional offers'; ?></div>
                <div class="box_control box_control_def">
                    <div class="arrov_box arrov_def">
                        <div class="swiper-button-prev-slide-new swiper-button-prev-slide-def">
                            <svg class="icon">
                                <use xlink:href="#Arrow-left-slide"></use>
                            </svg>
                        </div>
                        <div class="swiper-button-next-slide-new swiper-button-next-slide-def">
                            <svg class="icon">
                                <use xlink:href="#Arrow-slide-right"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide_wrapper">
                <div class="swiper-container slider_new">
                    <div class="swiper-wrapper">
                        <?php foreach ($products as $product_id) {
                        $product = wc_get_product( $product_id );
                        ?>
                            <div class="swiper-slide">
                                <div class="img_box_shadow">
                                    <a href="<?php the_permalink($product_id); ?>">
                                        <img src="<?php echo get_the_post_thumbnail_url($product_id, 'full') ?>" alt="">
                                    </a>
                                    <?php
                                    if ( $product->is_type( 'variable' ) ) {
                                        if($product->get_variation_sale_price() !== $product->get_variation_regular_price()) { ?>
                                            <div class="sale_text">sale</div>
                                        <?php
                                        }
                                    } else {
                                        if(!empty($product->get_sale_price())) { ?>
                                            <div class="sale_text">sale</div>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="icons_box">
                                    <?php if(!empty(get_field('options', $product_id))) { ?>
                                        <?php foreach (get_field('options', $product_id) as $option) { ?>
                                            <?php if($option == 'color') { ?>
                                                <div title="Color">
                                                    <svg class="icon">
                                                        <use xlink:href="#paint"></use>
                                                    </svg>
                                                </div>
                                            <?php } elseif($option == 'leather') { ?>
                                                <div title="Leather">
                                                    <svg class="icon">
                                                        <use xlink:href="#leather"></use>
                                                    </svg>
                                                </div>
                                            <?php } elseif($option == 'tkan') { ?>
                                                <div title="Leather and Cloth">
                                                    <svg class="icon">
                                                        <use xlink:href="#fabric"></use>
                                                    </svg>
                                                </div>
                                            <?php } elseif($option == 'size') { ?>
                                                <div title="Resize">
                                                    <svg class="icon">
                                                        <use xlink:href="#arrov"></use>
                                                    </svg>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <div class="name_product">
                                    <a href="<?php the_permalink($product_id); ?>">
                                        <?php echo $product->get_title(); ?>
                                    </a>
                                </div>
                                <div class="row_btn row_custom jcsb aic">
                                    <a href="<?php the_permalink($product_id); ?>" class="btn_def btn_def_3">
                                        <span><?php echo 'More details'; ?></span>
                                        <svg class="icon">
                                            <use xlink:href="#Arrow-slide-right"></use>
                                        </svg>
                                    </a>
                                    <div class="price_box">
                                        <?php
                                        if ( $product->is_type( 'variable' ) ) :
                                            if($product->get_variation_sale_price() !== $product->get_variation_regular_price()) { ?>
                                                <div class="old"><?php echo get_exerpt_price_html($product->get_variation_regular_price()); ?></div>
                                                <div class="price"><?php echo get_exerpt_price_html($product->get_variation_sale_price()); ?></div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class="price"><?php echo get_exerpt_price_html($product->get_variation_price()); ?></div>
                                            <?php
                                            }
                                        else :
                                            if(!empty($product->get_sale_price())) { ?>
                                                <div class="old"><?php echo get_exerpt_price_html($product->get_regular_price()); ?></div>
                                                <div class="price"><?php echo get_exerpt_price_html($product->get_sale_price()); ?></div>
                                            <?php } else { ?>
                                                <div class="price"><?php echo get_exerpt_price_html($product->get_price()); ?></div>
                                            <?php }
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    endif;
}

function true_register_wp_sidebars() {

    register_sidebar(
        array(
            'id' => 'filter',
            'name' => 'Column filter',
            'description' => 'Drag and drop widgets here to add them to the sidebar.',
            'before_widget' => '<div id="%1$s" class="side widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        )
    );
}

add_action( 'widgets_init', 'true_register_wp_sidebars' );

/*
** Disable tabs on product page
*/

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] ); // Remove the "Description" tab
    unset( $tabs['reviews'] );

 	$tabs['additional_information']['title'] = 'Характеристики';

 	$tabs['shiping']['title'] = 'Доставка и оплата';
 	$tabs['shiping']['priority'] = 30;
 	$tabs['shiping']['callback'] = 'woocommerce_product_shiping_tab';

return $tabs;
}

function woocommerce_product_shiping_tab() {
?>
		<div class="delivery_wrap">
	        <div class="title_def">Delivery</div>
	        <div class="box_sadow"><span>Our customers are our partners and friends.</span>, we cannot imagine
	        	let them fail, so we closely monitor the quality of the goods and packaging before by shipment!
	        </div>
	        <p>You can easily make a purchase from any city in Russia, Kazakhstan and Belarus. Can independently offer a transport company, and if possible, we will send the order with her help. Pickup of goods from a warehouse in Moscow is possible.</p>
	        <p>Courier delivery to the apartment is carried out in Moscow. To other cities of Russia - using the services of transport companies.</p>
	        <p>The order is transferred to the buyer only if there is an identity document of the recipient. If the delivered goods are received by another person, not the buyer, then a power of attorney is required. If at least one of the above conditions is not met, then the ordered goods will not be transferred to the recipient. In this case, the price of the idle run of the logistician lies entirely with the Buyer! Be careful, prepare all the documents, why overpay</p>

	        <h3 class="title_3">Cost of delivery</h3>

	        <p>Our products are diverse in size and volume: from a small interior decoration to a sofa requiring several movers for transportation. Therefore, the cost of delivery is calculated individually by the manager when placing an order.</p>

	        <h3 class="title_3">Delivery in Moscow and Moscow Region</h3>

	        <p>The cost of delivery within the Moscow Ring Road by the Gruzovichkof company is from 2000 rubles.</p>

	        <p>The services of loaders are calculated on the spot at the tariffs of the transport company (~ 500 rubles / 1 person; manual lifting + 100 rubles / floor for each loader).<a href="#">Learn more</a></p>

	        <p>If necessary, we can deliver any goods you ordered in the Moscow region. The preliminary cost of delivery in the region will be announced to you by the manager of our company. If the order is required to be delivered to a rural area or a suburban holiday village, then you will need a detailed location map to the specified location.</p>

	        <h3 class="title_3">Delivery to the Regions</h3>
	        <p>The average cost of delivery to the entrance in your city depends on the weight and dimensions of the goods, as well as the delivery address. Delivery is paid directly to the courier upon receipt of the goods. You can calculate the cost on the transport campaign calculator:<a href="#"> link</a></p>

	        <p>Before you sign on the waybill or waybill, be sure to check for damage to the packaging and completeness.</p>

	        <h3 class="title_3">Delivery terms</h3>

	        <p>The production and delivery period is on average 25-30 days (no more than 40 days under the contract). With large runs or the manufacture of furniture according to individual models of the customer, the terms can be increased by agreement. Shipment of goods in stock is carried out within 1-2 business days from the date of payment of the order.</p>

	        <p>Delivery is carried out on any day convenient for you. On weekdays from 10:00 to 18:00. Other delivery times are negotiated separately. Delivery in Russia (outside of Moscow and the Moscow region) is carried out by transport companies and is paid separately. Shipping cost depends on the size of the purchase. Find out delivery rates in Russia from our managers.</p>
	        <div class="title_def">Payment</div>
	        <div class="title_icon aic">
	            <div class="icon">
	                <svg>
	                    <use xlink:href="#money"></use>
	                </svg>

	            </div>
	            <div class="text">Cash at the showroom in Moscow</div>
	        </div>
	        <p>You can pay in cash / by credit card directly when placing an order in the showroom.</p>
	        <div class="title_icon aic">
	            <div class="icon">
	                <svg>
	                    <use xlink:href="#bill"></use>
	                </svg>

	            </div>
	            <div class="text">Cashless payments</div>
	        </div>
	        <p>After the order is confirmed, the manager will send an invoice to the specified e-mail, which can be paid at any nearest bank or Internet banking. After making the payment, you will send us the payment order by e-mail.</p>
	        <p>Payment by bank transfer is carried out on the basis of an invoice with fixed prices valid for 5 business days on the day of discharge.</p>
	        <div class="title_icon aic">
	            <div class="icon">
	                <svg>
	                    <use xlink:href="#credit-card"></use>
	                </svg>

	            </div>
	            <div class="text">Online payment on the site by card</div>
	        </div>

	        <p>Payment by bank cards on the site is carried out through the payment system of Alfa Bank JSC after the order is confirmed by the manager by phone. After placing the order, the goods are reserved for payment for 5 days.</p>

	        <div class="title_list">We accept credit card payments</div>
	        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Group382.png" alt="">
	        <ul>
				<li> When ordering, select "Online Card Payment"; </li>
				<li> A manager will contact you to confirm the order and discuss the terms of delivery; </li>
				<li> Enter payment data: - bank card number
				<ul>
					<li> - validity period of the card </li>
					<li> - map username </li>
					<li> - CVC2 / CVV2 / CID code </li>
				</ul>
				</li>
				<li> A receipt will be sent to your mail. </li>
				</ul>

	        <p>When paying by credit card, the refund is made to the card with which the payment was made. More about return If you still have questions about payment or delivery, you can ask them by phone <a
	                href="tel:8 (800) 999-99-99">8 (800) 999-99-99</a> or in a letter <a
	                href="mailto:info@old-loft.com">info@old-loft.com</a>
	        </p>
	        <h3 class="title_3">Quality control and insurance claims:</h3>
	        <p>Our customers are our partners and friends, we cannot afford to let them down, so we closely monitor the quality of the goods and packaging before shipment.</p>

	        <p>1) . All goods are checked for defects before being shipped at the warehouse. After manufacturing at the factory and immediately before sending the goods from the warehouse in Moscow, we take photos and videos of your order from different angles. We send materials to e-mail or to any convenient messenger.</p>

	        <p>2). OLD-LOFT does not provide and is not responsible for the transportation of goods through the territory of the Russian Federation from the warehouse of the seller to the address of the buyer. A third-party delivery service is provided. The buyer can independently pick up the cargo by any Transport company and order an order check (commercial act of inspection of internal contents).</p>

	        <p>3). In the event that OLD LOFT employees make an application to the shopping center for shipment of the order, the transport company that carries the cargo also bears the responsibility for transportation. And claims on the received broken goods are accepted by the TC!</p>

	        <p>4) . If the Client needs to ship the order of another shopping mall - the client makes an application for shipment on their own.</p>

	        <p>
	            5) . OLD LOFT employees make requests for delivery of the shopping center "PEK". In the event that an application to the shopping center for shipment of an order is made by OLD LOFT employees, the application must indicate:
				• crate,
				• full insurance,
				• total cost of goods.
	        </p>

	        <p>
	            6). If the Transport company refuses to compensate the customer for the loss or damage of the goods, explaining this by the fact that:
				• insurance was not indicated in the application,
				• the full price was not indicated in the application
				• or TC transfers the blame for damage to the goods to the Seller
	        </p>

	        <p>
	            This is a deliberate lie, backdating of data and an attempt to evade responsibility. We kindly ask you to pay attention to this information of our customers! We do our best to prevent such applicants from happening (carefully pack the goods, check the shopping mall in advance and filter those for whom we receive negative reviews). But it is not possible to influence the decency and integrity of Russian shopping malls; the Seller does not have such resources. Our customers are our partners and friends, we cannot afford to let them down, so we closely monitor the quality of the goods and packaging before shipment!
	        </p>
	    </div>
<?php
}

function wc_dropdown_variation_attribute_options( $args = array() ) {
	$args = wp_parse_args(
		apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ),
		array(
			'options'          => false,
			'attribute'        => false,
			'product'          => false,
			'selected'         => false,
			'name'             => '',
			'id'               => '',
			'class'            => '',
			'show_option_none' => __( 'Choose an option', 'woocommerce' ),
		)
	);

	// Get selected value.
	if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
		$selected_key     = 'attribute_' . sanitize_title( $args['attribute'] );
		$args['selected'] = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
	}

	$options               = $args['options'];
	$product               = $args['product'];
	$attribute             = $args['attribute'];
	$name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
	$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
	$class                 = $args['class'];
	$show_option_none      = (bool) $args['show_option_none'];
	$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

	if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
		$attributes = $product->get_variation_attributes();
		$options    = $attributes[ $attribute ];
	}

	$html = '';
	if ( ! empty( $options ) ) {
		if ( $product && taxonomy_exists( $attribute ) ) {
			$terms = wc_get_product_terms(
				$product->get_id(),
				$attribute,
				array(
					'fields' => 'all',
				)
			);
			$html .= '<div class="attribute_image" data-attr-term="" data-attr-tax="' . esc_attr( $id ) . '"><img src="' . wp_get_attachment_image_url(get_field('image', 'term_'.$terms[0]->term_id)) . '"></div>';
			$html .= '<div class="attribute_list"><div class="title_list"><span>'. $terms[0]->name .'</span><div class="icon"><svg><use xlink:href="#arrov_orange"></use></svg></div></div><ul>';
			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $options, true ) ) {
					$html .= '<li term-slug="' . $term->slug . '" image_url="' . wp_get_attachment_image_url(get_field('image', 'term_'.$term->term_id)) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</li>';
				}
			}
			$html .= '</ul></div>';
		}
	}


	$html  .= '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';

	if ( ! empty( $options ) ) {
		if ( $product && taxonomy_exists( $attribute ) ) {
			// Get terms if this is a taxonomy - ordered. We need the names too.
			$terms = wc_get_product_terms(
				$product->get_id(),
				$attribute,
				array(
					'fields' => 'all',
				)
			);

			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $options, true ) ) {
					$html .= '<option data-term_id="'.$term->term_id.'" value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</option>';
				}
			}
		} else {
			foreach ( $options as $option ) {
				// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
				$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
				$html    .= '<option data-term_id="'.$term->term_id.'" value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
			}
		}
	}

	$html .= '</select>';

	echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args ); // WPCS: XSS ok.
}

function add_button_to_chechout_visible() {
    echo '<div class="wrap_def">
            <div class="row_custom">
            <a href="#" class="btn_def_1 btn_def" id="go_to_step_two">
                <span class="text">' . "Proceed to checkout" . '</span>
                <span class="icon">
                    <svg>
                        <use xlink:href="#icon-arrow-right"></use>
                    </svg>
                </span>
            </a>
          </div>
        </div>';
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {

    $fields['billing']['billing_first_name']['placeholder'] = 'Name';
    unset($fields['billing']['billing_first_name']['label']);

    $fields['billing']['billing_last_name']['placeholder'] = 'Surname';
    unset($fields['billing']['billing_last_name']['label']);

    $fields['billing']['billing_phone']['placeholder'] = 'Telephone';
    unset($fields['billing']['billing_phone']['label']);

    $fields['billing']['billing_email']['placeholder'] = 'Email';
    unset($fields['billing']['billing_email']['label']);

    $fields['billing']['billing_country']['placeholder'] = 'Country';
    $fields['billing']['billing_country']['class'][1] = 'country';
    unset($fields['billing']['billing_country']['label']);

    $fields['billing']['billing_city']['placeholder'] = 'Town';
    $fields['billing']['billing_city']['class'][1] = 'city';
    unset($fields['billing']['billing_city']['label']);

    $fields['billing']['billing_address_1']['placeholder'] = 'Street';
    $fields['billing']['billing_address_1']['class'][1] = 'street';
    unset($fields['billing']['billing_address_1']['label']);

    $fields['billing']['billing_address_2']['placeholder'] = 'House number';
    $fields['billing']['billing_address_2']['class'][1] = 'house_number';
    unset($fields['billing']['billing_address_2']['label']);

    $fields['billing']['billing_address_1']['required'] = false;
    $fields['billing']['billing_address_2']['required'] = false;

    $fields['billing']['billing_email']['priority'] = 30;
    $fields['billing']['billing_phone']['priority'] = 35;
    $fields['billing']['billing_city']['priority'] = 45;

    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_state']);
    unset($fields['order']['order_comments']);
    return $fields;
}

function tb_text_strings( $translated_text, $text, $domain ) {

    switch ( $translated_text ) {
        case 'House number and street name' :
            $translated_text =  'Street';
            break;
        case 'Additional address information (optional)' :
            $translated_text =  'House number';
            break;
        case 'Код купона' :
            $translated_text =  'Have a promo code? Enter here' ;
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'tb_text_strings', 20, 3 );

function remove_fields_in_checkout($fields) {
    $fields['address_1']['label'] = 'Street';
    $fields['address_1']['required'] = false;
    $fields['address_2']['label'] = 'House number';
    $fields['address_2']['required'] = false;
    $fields['city']['label'] = 'Town';
    $fields['city']['priority'] = 45;
    return $fields;
}

add_action('woocommerce_default_address_fields', 'remove_fields_in_checkout');

add_filter( 'woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2 );

function wc_wc20_variation_price_format( $price, $product ) {
    // Main Price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    if(is_single()) {
        $price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
    } else {
        $price = $prices[0] !== $prices[1] ? $prices[0]: $prices[0];
    }

    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    if(is_single()) {
        $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
    } else {
        $saleprice = $prices[0] !== $prices[1] ? $prices[0] : $prices[0];
    }

    if ( $price !== $saleprice ) {
        if(is_single()) {
            $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
        } else {
            $price = '<del>' . get_exerpt_price_html($saleprice) . '</del> <ins>' . get_exerpt_price_html($price) . '</ins>';
        }

    }

    return $price;
}

function show_delivery() {
    if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
        <div class="shipping_block">
            <?php
            do_action( 'woocommerce_review_order_before_shipping' );
            echo "<h3>" . 'Select delivery type' . "</h3>";
            wc_cart_totals_shipping_html();
            do_action( 'woocommerce_review_order_after_shipping' );
            ?>
        </div>
    <?php
    endif;
}

function recalculate_price_product_to_add($cart_obj) {
    foreach( $cart_obj->get_cart() as $key=>$value ) {
        $logic = false;
        if(isset($custom_cart_data['tmcartepo']) && !empty($custom_cart_data['tmcartepo'])){
            foreach ($value['tmcartepo'] as $attributes) {
               if(strripos($attributes['cssclass'],'logic_attr') !== false) {
                $logic = true;
               }
            }
        }
        if($logic) {
           $value['data']->set_price(1);
        }

    }
}
add_action( 'woocommerce_before_calculate_totals', 'recalculate_price_product_to_add', 10, 1 );

function show_tabs() {
    $product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

    if ( ! empty( $product_tabs ) ) : ?>

        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
                <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                    <?php
                    if ( isset( $product_tab['callback'] ) ) {
                        call_user_func( $product_tab['callback'], $key, $product_tab );
                    }
                    ?>
                </div>
            <?php endforeach; ?>

            <?php do_action( 'woocommerce_product_after_tabs' ); ?>
        </div>

    <?php endif;
}

function woocommerce_checkout_payment_variations() {

    if ( WC()->cart->needs_payment() ) {
        $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
        WC()->payment_gateways()->set_current_gateway( $available_gateways );
    } else {
        $available_gateways = array();
    }
    ?>
    <div class="payment_block">
        <h3><?php echo 'Select a Payment Method'; ?></h3>
        <ul class="wc_payment_methods payment_methods methods">
            <?php
            if ( ! empty( $available_gateways ) ) {
                foreach ( $available_gateways as $gateway ) {
                    wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                }
            } else {
                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>';
            }
            ?>
        </ul>
    </div>
    <?php
}

add_filter( 'woocommerce_account_menu_items', 'remove_downloads_link' );

function remove_downloads_link( $items ) {
    if ( isset( $items['downloads'] ) ) {
        unset( $items['downloads'] );
    }
    return $items;
}

function show_stocks() {
    global $product;

    if($product->get_stock_quantity() !== 0 || !empty($product->get_stock_quantity())) {
        $text = '<div class="stocks_quantity">' . "Item in stock" . '</div>';
        echo sprintf($text, $product->get_stock_quantity());
    } elseif ($product->get_stock_quantity() == 0) {
        echo '<div class="stocks_quantity">' . "Not available" . '</div>';
    }
}

add_filter("loop_shop_per_page", function($cols) {

		$category = get_queried_object();
		$current_cat_id = $category->term_id;

        $current_tax = $category->taxonomy;
        if($current_tax == 'product_cat'){
            return 50;
        }else{
            return 9;
        }

}, 20);

function wc_order_statuses_filter( $order_statuses ) {
    $order_statuses = array(
        'wc-pending'    => _x( 'Pending payment', 'Order status', 'woocommerce' ),
        'wc-processing' => _x( 'Processing', 'Order status', 'woocommerce' ),
        'wc-on-hold'    => _x( 'Submitted', 'Order status', 'woocommerce' ),
        'wc-completed'  => _x( 'Completed', 'Order status', 'woocommerce' ),
        'wc-cancelled'  => _x( 'Cancelled', 'Order status', 'woocommerce' ),
        'wc-refunded'   => _x( 'Refunded', 'Order status', 'woocommerce' ),
        'wc-failed'     => _x( 'Failed', 'Order status', 'woocommerce' ),
    );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wc_order_statuses_filter' );

add_filter( 'woocommerce_bacs_process_payment_order_status','filter_process_payment_order_status_callback', 10, 2 );
add_filter( 'woocommerce_cod_process_payment_order_status','filter_process_payment_order_status_callback', 10, 2 );
function filter_process_payment_order_status_callback( $status, $order ) {
    return 'processing';
}

add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

function custom_override_default_address_fields( $fields ) {
    
    unset($fields['company']);
    unset($fields['state']);
    unset($fields['postcode']);
    return $fields;
}