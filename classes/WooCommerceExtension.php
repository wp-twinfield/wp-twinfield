<?php

namespace Pronamic\WP\Twinfield\Plugin;

/**
 * Title: Twinfield WooCommerce plugin
 * Description:
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 */
class WooCommerceExtension {
	/**
	 * Constructs and initialize plugin
	 *
	 * @param unknown $file
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		// Required plugins.
		if ( ! defined( 'WC_VERSION' ) ) {
			return;
		}

		// Actions.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Post types.
		add_post_type_support( 'product', 'twinfield_article' );
		add_post_type_support( 'shop_coupon', 'twinfield_article' );
		add_post_type_support( 'shop_order', 'twinfield_customer' );
		add_post_type_support( 'shop_order', 'twinfield_invoiceable' );

		// Twinfield.
		add_action( 'twinfield_post_sales_invoice', array( $this, 'twinfield_post_sales_invoice' ), 20, 2 );
		add_action( 'twinfield_post_customer_id', array( $this, 'twinfield_post_customer_id' ), 20, 2 );
		add_action( 'twinfield_post_customer', array( $this, 'twinfield_post_customer' ), 20, 2 );

		//  Manage `shop_order` posts columns.
		add_filter( 'manage_shop_order_posts_columns', array( $this, 'manage_shop_order_posts_columns' ), 100 );
		
		// Order completed.
		add_action( 'woocommerce_order_status_completed', array( $this, 'woocommerce_order_status_completed' ) );
	}

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		/**
		 * WooCommerce
		 */
		add_settings_section(
			'twinfield_woocommerce',
			__( 'WooCommerce', 'twinfield' ),
			'__return_false',
			'twinfield'
		);

		add_settings_field(
			'twinfield_woocommerce_shipping_method_article_codes',
			__( 'Shipping Method Articles', 'twinfield' ),
			array( $this, 'field_shipping_methods' ),
			'twinfield',
			'twinfield_woocommerce'
		);

		add_settings_field(
			'twinfield_woocommerce_no_tax_vat_code',
			__( 'No Tax Vat Code', 'twinfield' ),
			__NAMESPACE__ . '\SettingFields::render_text',
			'twinfield',
			'twinfield_woocommerce',
			array(
				'label_for'   => 'twinfield_woocommerce_no_tax_vat_code',
				'classes'     => array( 'regular-text', 'code' ),
				/* translators: use same translations as on Twinfield.com. */
				'description' => _x( 'This VAT code is used for order items without tax.', 'twinfield' ),
			)
		);

		if ( wc_tax_enabled() ) {
			add_settings_field(
				'twinfield_woocommerce_tax_rate_vat_codes',
				__( 'Tax Rate Codes', 'twinfield' ),
				array( $this, 'field_tax_rates' ),
				'twinfield',
				'twinfield_woocommerce'
			);
		}

		register_setting( 'twinfield', 'twinfield_woocommerce_shipping_method_article_codes' );
		register_setting( 'twinfield', 'twinfield_woocommerce_shipping_method_subarticle_codes' );

		register_setting( 'twinfield', 'twinfield_woocommerce_no_tax_vat_code' );
		register_setting( 'twinfield', 'twinfield_woocommerce_tax_rate_vat_codes' );
	}

	/**
	 * Manage shop order posts columns.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/5.0.3/wp-admin/includes/class-wp-posts-list-table.php#L588-L608
	 *
	 * @param array $post_columns Post columns array.
	 * @return array
	 */
	public function manage_shop_order_posts_columns( $post_columns ) {
		$post_columns['twinfield_invoice'] = __( 'Twinfield Invoice', 'twinfield' );

		return $post_columns;
	}

	public function field_shipping_methods( $args ) {
		// Get the WC Shopping class
		$wc_shipping = new \WC_Shipping();

		// Get all shipping methods
		$shipping_methods = $wc_shipping->load_shipping_methods();

		// Custom field.
		include plugin_dir_path( $this->plugin->file ) . 'admin/settings-field-woocommerce-shipping-methods.php';
	}

	public function field_tax_rates( $args ) {
		// Tax classes
		// @see https://github.com/woothemes/woocommerce/blob/v2.2.3/includes/admin/settings/class-wc-settings-tax.php#L45-L52
		$sections = array(
			'standard' => __( 'Standard Rates', 'twinfield' ),
		);

		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'woocommerce_tax_classes' ) ) ) );

		if ( $tax_classes ) {
			foreach ( $tax_classes as $class ) {
				$sections[ sanitize_title( $class ) ] = $class;
			}
		}

		// Custom field.
		include plugin_dir_path( $this->plugin->file ) . 'admin/settings-field-woocommerce-tax-rates.php';
	}

	public function get_tax_class_vat_code( $tax_class ) {
		$tax_class = empty( $tax_class ) ? 'empty' : $tax_class;

		$tax_classes_vat_codes = get_option( 'twinfield_woocommerce_tax_classes_vat_codes' );
		$tax_classes_vat_codes = is_array( $tax_classes_vat_codes ) ? $tax_classes_vat_codes : array();

		if ( isset( $tax_classes_vat_codes[ $tax_class ] ) ) {
			return $tax_classes_vat_codes[ $tax_class ];
		}
	}

	public function get_shipping_method_article_code( $method_id ) {
		$article_code = null;

		$shipping_method_article_codes = get_option( 'twinfield_woocommerce_shipping_method_article_codes' );
		$shipping_method_article_codes = is_array( $shipping_method_article_codes ) ? $shipping_method_article_codes : array();

		if ( isset( $shipping_method_article_codes[ $method_id ] ) ) {
			$article_code = $shipping_method_article_codes[ $method_id ];
		}

		if ( empty( $article_code ) ) {
			$article_code = get_option( 'twinfield_default_article_code' );
		}

		return $article_code;
	}

	public function get_shipping_method_subarticle_code( $method_id ) {
		$article_code = null;

		$shipping_method_subarticle_codes = get_option( 'twinfield_woocommerce_shipping_method_subarticle_codes' );
		$shipping_method_subarticle_codes = is_array( $shipping_method_subarticle_codes ) ? $shipping_method_subarticle_codes : array();

		if ( isset( $shipping_method_article_codes[ $method_id ] ) ) {
			$article_code = $shipping_method_article_codes[ $method_id ];
		}

		if ( empty( $article_code ) ) {
			$article_code = get_option( 'twinfield_default_subarticle_code' );
		}

		return $article_code;
	}

	public function twinfield_post_customer_id( $customer_id, $post_id ) {
		// Empty.
		if ( ! empty( $customer_id ) ) {
			return $customer_id;
		}

		// Check Post Type.
		if ( 'shop_order' !== get_post_type( $post_id ) ) {
			return $customer_id;
		}

		// Order.
		$order = wc_get_order( $post_id );

		// Customer.
		$wc_customer_id = $order->customer_id;

		$value = get_user_meta( $wc_customer_id, 'twinfield_customer_id', true );

		if ( ! empty( $value ) ) {
			$customer_id = $value;
		}

		return $customer_id;
	}

	public function twinfield_post_customer( $customer, $post_id ) {
		// Check Post Type.
		if ( 'shop_order' !== get_post_type( $post_id ) ) {
			return $customer;
		}

		// Order.
		$order = wc_get_order( $post_id );

		// Billing.
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L488-L496
		$billing_company = $order->get_billing_company();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L802-L810
		$billing_full_name = $order->get_formatted_billing_full_name();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L498-L506
		$billing_address_1 = $order->get_billing_address_1();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L508-L516
		$billing_address_2 = $order->get_billing_address_2();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L538-L546
		$billing_postcode = $order->get_billing_postcode();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L518-L526
		$biliing_city = $order->get_billing_city();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L548-L556
		$billing_country = $order->get_billing_country();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L558-L566
		$billing_email = $order->get_billing_email();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L568-L576
		$billing_phone = $order->get_billing_phone();

		// Shipping.
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L598-L606
		$shipping_company = $order->get_shipping_company();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L802-L810
		$shipping_full_name = $order->get_formatted_shipping_full_name();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L608-L616
		$shipping_address_1 = $order->get_shipping_address_1();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L618-L626
		$shipping_address_2 = $order->get_shipping_address_2();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L648-L656
		$shipping_postcode = $order->get_shipping_postcode();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L628-L636
		$shipping_city = $order->get_shipping_city();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L548-L556
		$shipping_country = $order->get_shipping_country();

		// Customer.
		$customer->set_name( empty( $billing_company ) ? $billing_full_name : $billing_company );

		// Financials.
		$financials = $customer->get_financials();
		$financials->set_due_days( 14 );
		$financials->set_ebilling( true );
		$financials->set_ebillmail( $billing_email );

		// Credit Management.
		$credit_management = $customer->get_credit_management();
		$credit_management->set_send_reminder( 'email' );
		$credit_management->set_reminder_email( $billing_email );

		// Billing Address.
		$address = $customer->new_address();

		$address->set_type( \Pronamic\WP\Twinfield\AddressTypes::INVOICE );
		$address->set_name( $billing_company );
		$address->set_field_1( $billing_full_name );
		$address->set_field_2( $billing_address_1 );
		$address->set_field_3( $billing_address_2 );
		$address->set_postcode( $billing_postcode );
		$address->set_city( $biliing_city );
		$address->set_country( $billing_country );

		$address->set_telephone( $billing_phone );
		$address->set_email( $billing_email );

		// Shipping Address.
		$address = $customer->new_address();

		$address->set_type( \Pronamic\WP\Twinfield\AddressTypes::POSTAL );
		$address->set_name( $shipping_company );
		$address->set_field_1( $shipping_full_name );
		$address->set_field_2( $shipping_address_1 );
		$address->set_field_3( $shipping_address_2 );
		$address->set_postcode( $shipping_postcode );
		$address->set_city( $shipping_city );
		$address->set_country( $shipping_country );

		$address->set_telephone( $billing_phone );
		$address->set_email( $billing_email );

		// Return.
		return $customer;
	}

	public function get_order_item_vat_code( $item ) {
		if ( ! is_callable( array( $item, 'get_taxes' ) ) ) {
			return;
		}

		$item_taxes = $item->get_taxes();

		$item_taxes_total = $item_taxes['total'];
		$item_taxes_total = array_filter( $item_taxes_total, 'strlen' );

		if ( empty( $item_taxes_total ) ) {
			return get_option( 'twinfield_woocommerce_no_tax_vat_code' );
		}

		$rate_id = key( $item_taxes_total );

		$tax_rate_vat_codes = get_option( 'twinfield_woocommerce_tax_rate_vat_codes' );
		$tax_rate_vat_codes = is_array( $tax_rate_vat_codes ) ? $tax_rate_vat_codes : array();

		if ( isset( $tax_rate_vat_codes[ $rate_id ] ) ) {
			return $tax_rate_vat_codes[ $rate_id ];
		}

		return null;
	}

	/**
	 * Twinfield post customer
	 *
	 * @param SalesInvoice $invoice
	 * @param int          $post_id
	 */
	public function twinfield_post_sales_invoice( $invoice, $post_id ) {
		if ( 'shop_order' !== get_post_type( $post_id ) ) {
			return $invoice;
		}

		// Defaults.
		$twinfield_default_article_code    = get_option( 'twinfield_default_article_code' );
		$twinfield_default_subarticle_code = get_option( 'twinfield_default_subarticle_code' );

		// Order.
		$order = wc_get_order( $post_id );

		/*
		 * Items.
		 *
		 * @link https://github.com/woothemes/woocommerce/blob/2.5.3/includes/abstracts/abstract-wc-order.php#L1118-L1150
		 * @link https://github.com/woocommerce/woocommerce/blob/3.5.3/includes/admin/meta-boxes/views/html-order-items.php
		 * @link https://github.com/woocommerce/woocommerce/blob/3.5.3/includes/admin/meta-boxes/views/html-order-item.php
		 * @link https://github.com/woocommerce/woocommerce/blob/3.5.3/includes/class-wc-order-item-tax.php
		 */
		foreach ( $order->get_items() as $item ) {
			$line = $invoice->new_line();

			// Find and article and subarticle id if set
			$article_code = get_post_meta( $item['product_id'], '_twinfield_article_code', true );

			if ( empty( $article_code ) ) {
				$article_code = $twinfield_default_article_code;
			}

			$subarticle_code = get_post_meta( $item['product_id'], '_twinfield_subarticle_code', true );

			if ( empty( $subarticle_code ) ) {
				$subarticle_code = $twinfield_default_subarticle_code;
			}

			$line->set_article( $article_code );
			$line->set_subarticle( $subarticle_code );
			$line->set_quantity( $item['qty'] );
			// @link https://github.com/woocommerce/woocommerce/blob/3.5.3/includes/abstracts/abstract-wc-order.php#L1535-L1557
			$line->set_units_price_excl( $order->get_item_total( $item, false ) );
			$line->set_vat_code( $this->get_order_item_vat_code( $item ) );
			$line->set_free_text_1( mb_substr( $item['name'], 0, 36 ) );
		}

		/*
		 * Fees.
		 *
		 * @link https://github.com/woothemes/woocommerce/blob/2.5.3/includes/abstracts/abstract-wc-order.php#L1221-L1228
		 */
		foreach ( $order->get_fees() as $item ) {
			$line = $invoice->new_line();

			$line->set_article( $twinfield_default_article_code );
			$line->set_subarticle( $twinfield_default_subarticle_code );
			$line->set_quantity( 1 );
			$line->set_units_price_excl( $order->get_item_total( $item, false ) );
			$line->set_free_text_1( mb_substr( __( 'Fee', 'twinfield' ), 0, 36 ) );
		}

		/*
		 * Shipping.
		 *
		 * @link https://github.com/woothemes/woocommerce/blob/2.5.3/includes/abstracts/abstract-wc-order.php#L1239-L1246
		 */
		foreach ( $order->get_shipping_methods() as $item ) {
			$line = $invoice->new_line();

			$line->set_article( $this->get_shipping_method_article_code( $item['method_id'] ) );
			$line->set_subarticle( $this->get_shipping_method_subarticle_code( $item['method_id'] ) );
			$line->set_quantity( 1 );
			$line->set_units_price_excl( $item['cost'] );
			$line->set_vat_code( $this->get_order_item_vat_code( $item ) );
			$line->set_free_text_1( mb_substr( $item['name'], 0, 36 ) );
		}

		return $invoice;
	}

	/**
	 * WooCommerce order status completed.
	 *
	 * @link https://github.com/woocommerce/woocommerce/blob/3.5.4/includes/class-wc-order.php#L339-L376
	 *
	 * @param int $order_id
	 */
	public function woocommerce_order_status_completed( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( false === $order ) {
			return;
		}

		$sales_invoice = $this->plugin->insert_twinfield_sales_invoice_from_post( $order_id );

		$number = $sales_invoice->get_header()->get_number();

		if ( empty( $number ) ) {
			return;
		}

		$order->add_order_note( 
			sprintf(
				/* translators: %s: invoice number */
				__( 'Created Twinfield invoice %s.', 'twinfield' ),
				$number
			)
		);
	}
}
