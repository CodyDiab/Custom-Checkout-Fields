/* Add Custom Checkout Fields */

function add_salutation_fields( $fields ) {
    $fields['salutation_'] = array(
        'label'        => __( 'Salutation' ),
        'type'        => 'select',
        'class'        => array( 'form-row-wide' ),
        'priority'     => 1,
        'required'     => false,
        'options'       => array(
	    	'blank'		=> __( '' ),
	        'mr'	=> __( 'Mr.' ),
	        'ms'	=> __( 'Ms.' ),
	        'mrs' 	=> __( 'Mrs.' ),
            'dr' 	=> __( 'Dr.' ),
            'prof' 	=> __( 'Prof.' ),
            'mx' 	=> __( 'Mx.' )
			)
    );

    return $fields;
}
add_filter( 'woocommerce_billing_fields', 'add_salutation_fields' );

function add_suffix_fields( $fields ) {
    $fields['suffix_'] = array(
        'label'        => __( 'Suffix' ),
        'type'        => 'text',
        'class'        => array( 'form-row-wide' ),
        'priority'     => 25,
        'required'     => false,
        
    );

    return $fields;
}
add_filter( 'woocommerce_billing_fields', 'add_suffix_fields' );

function add_ranch_name_fields( $fields ) {
    $fields['ranch_name'] = array(
        'label'        => __( 'Ranch Name' ),
        'type'        => 'text',
        'class'        => array( 'form-row-wide' ),
        'priority'     => 35,
        'required'     => false,
        
    );

    return $fields;
}
add_filter( 'woocommerce_billing_fields', 'add_ranch_name_fields' );

function is_member_in_cart() {
    // Add your special product IDs here
    $ids = array(314,322,323,324,325,326,327);

    foreach( WC()->cart->get_cart() as $cart_item ){
        $product_id = version_compare( WC_VERSION, '3.0', '<' ) ? $cart_item['data']->id : $cart_item['data']->get_id();
        if( in_array( $cart_item['data']->get_id(), $ids ) )
            return true;
    }
    return false;
}

add_action('woocommerce_after_order_notes', 'member_checkout_field');
function member_checkout_field( $checkout ) {
	if( is_member_in_cart() ){
		echo '<div id="member_checkout_fields"><h3 style="padding-top: 30px;">'.__('Members Information (If different from billing)').'</h3>';	
		woocommerce_form_field( 'member_first', array( 
			'type' 			=> 'text', 
			'class' 		=> array('member-first form-row-first'), 
			'label' 		=> __(' Member First Name'),
			'required'		=> false,
			'placeholder' 	=> __(''),
			), $checkout->get_value( 'member_first' ));

			woocommerce_form_field( 'member_last', array( 
			'type' 			=> 'text', 
			'class' 		=> array('member-last form-row-last'), 
			'label' 		=> __('Member Last Name'),
			'required'		=> false,
			'placeholder' 	=> __(''),
			), $checkout->get_value( 'member_last' ));
		
		woocommerce_form_field( 'member_address', array( 
			'type' 			=> 'text', 
			'class' 		=> array('member-address form-row-full'), 
			'label' 		=> __('Member Address'),
			'required'		=> false,
			'placeholder' 	=> __('House number and street name'),
			), $checkout->get_value( 'member_address' ));

		woocommerce_form_field( 'member_addresst_2', array( 
			'type' 			=> 'text', 
			'class' 		=> array('member-address-2 form-row-full'), 
			'label' 		=> __('Member Address'),
			'required'		=> false,
			'placeholder' 	=> __('Apartment, suite, unit, etc.(optional)'),
			), $checkout->get_value( 'member_address_2' ));

			woocommerce_form_field( 'member_city', array( 
			'type' 			=> 'text', 
			'class' 		=> array('member-town form-row-full'), 
			'label' 		=> __('Member Town/City'),
			'required'		=> false,
			'placeholder' 	=> __(''),
			), $checkout->get_value( 'member_city' ));

			woocommerce_form_field( 'member_state', array( 
			'type' 			=> 'state', 
			'class' 		=> array('member-state form-row-last'), 
			'label' 		=> __('Member State'),
			'required'		=> false,
			'placeholder' 	=> __(''),
			), $checkout->get_value( 'member_state' ));
		
		woocommerce_form_field( 'member_zip', array( 
			'type' 			=> 'number', 
			'class' 		=> array('member-zip form-row-full'), 
			'label' 		=> __('Member Zip'),
			'required'		=> false,
			'placeholder' 	=> __(''),
			), $checkout->get_value( 'member_zip' ));

	
		echo '</div>';
	}
}

/* Update the user meta with custom field values */
add_action('woocommerce_checkout_update_user_meta', 'member_checkout_field_update_user_meta');

function member_checkout_field_update_user_meta( $user_id ) {
    if ($user_id && $_POST['salutation_']) {
		update_user_meta( $user_id, 'salutation_', esc_attr($_POST['salutation_']) );
	}
    if ($user_id && $_POST['suffix_']) {
		update_user_meta( $user_id, 'suffix_', esc_attr($_POST['suffix_']) );
	}
    if ($user_id && $_POST['ranch_name']) {
		update_user_meta( $user_id, 'ranch_name', esc_attr($_POST['ranch_name']) );
	}
	if ($user_id && $_POST['member_first']) {
		update_user_meta( $user_id, 'member_first', esc_attr($_POST['member_first']) );
	}
	if ($user_id && $_POST['member_last']) {
		update_user_meta( $user_id, 'member_last', esc_attr($_POST['member_last']) );
	}
	if ($user_id && $_POST['member_address']) {
		update_user_meta( $user_id, 'member_address', esc_attr($_POST['member_address']) );
	}

	if ($user_id && $_POST['member_address_2']) {
		update_user_meta( $user_id, 'member_address_2', esc_attr($_POST['member_address_2']) );
	}
	if ($user_id && $_POST['member_city']) {
		update_user_meta( $user_id, 'member_city', esc_attr($_POST['member_city']) );
	}
	if ($user_id && $_POST['member_state']) {
		update_user_meta( $user_id, 'member_state', esc_attr($_POST['member_state']) );
	}
	if ($user_id && $_POST['member_zip']) {
		update_user_meta( $user_id, 'member_zip', esc_attr($_POST['member_zip']) );
	}

}

/* Update the order meta with custom field values */
add_action('woocommerce_checkout_update_order_meta', 'member_checkout_field_update_order_meta');

function member_checkout_field_update_order_meta( $order_id ) {
	if ($_POST['member_first']) {
		update_post_meta( $order_id, ' Member First Name', esc_attr($_POST['member_first']));
	}
	if ($_POST['member_last']) {
		update_post_meta( $order_id, 'Member Last Name', esc_attr($_POST['member_last']));
	}
	if ($_POST['member_address']) {
		update_post_meta( $order_id, 'Member Address', 
esc_attr($_POST['member_address']));
	}
	if ($_POST['member_address_2']) {
		update_post_meta( $order_id, 'Member Address', esc_attr($_POST['member_address_2']));
	}
	if ($_POST['member_city']) {
		update_post_meta( $order_id, 'Member Town/City', esc_attr($_POST['member_city']));
	}
	if ($_POST['member_state']) {
		update_post_meta( $order_id, 'Member State', esc_attr($_POST['	member_state']));
	}
	if ($_POST['member_zip']) {
		update_post_meta( $order_id, 'Member Zip',
esc_attr($_POST['member_zip']));
	}
    if ($_POST['salutation_']) {
		update_post_meta( $order_id, 'Salutation',
esc_attr($_POST['salutation_']));
	}
    if ($_POST['suffix_']) {
		update_post_meta( $order_id, 'Suffix',
esc_attr($_POST['suffix_']));
	}
    if ($_POST['ranch_name']) {
		update_post_meta( $order_id, 'Ranch Name',
esc_attr($_POST['ranch_name']));
	}
}

/*remove order notes*/
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );