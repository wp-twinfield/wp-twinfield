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

	//////////////////////////////////////////////////

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		// Required plugins
		if ( ! defined( 'WC_VERSION' ) || ! class_exists( 'Pronamic\WP\Twinfield\Plugin\Plugin' ) ) {
			return;
		}

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_filter( 'woocommerce_integrations', array( $this, 'woocommerce_integrations' ) );

		// Text domain
		load_plugin_textdomain( 'twinfield_woocommerce', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );

		// Post types
		add_post_type_support( 'product', 'twinfield_article' );
		add_post_type_support( 'shop_coupon', 'twinfield_article' );
		add_post_type_support( 'shop_order', 'twinfield_customer' );
		add_post_type_support( 'shop_order', 'twinfield_invoiceable' );

		// Twinfield
		add_action( 'twinfield_post_sales_invoice', array( $this, 'twinfield_post_sales_invoice' ), 20, 2 );
		add_action( 'twinfield_post_customer', array( $this, 'twinfield_post_customer' ), 20, 2 );
	}

	//////////////////////////////////////////////////

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
/*
		add_settings_field(
			'twinfield_woocommerce_hipping_methods',
			__( 'Shipping Methods', 'twinfield' ),
			array( $this, 'field_shipping_methods' ),
			'twinfield',
			'twinfield_woocommerce'
		);

		add_settings_field(
			'twinfield_woocommerce_tax_rates',
			__( 'Tax Rates', 'twinfield' ),
			array( $this, 'field_tax_rates' ),
			'twinfield',
			'twinfield_woocommerce'
		);
*/
		register_setting( 'twinfield', 'twinfield_woocommerce_shipping_method_article_codes' );
		register_setting( 'twinfield', 'twinfield_woocommerce_tax_rates' );
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
			'standard' => __( 'Standard Rates', 'woocommerce' ),
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


	//////////////////////////////////////////////////

	/**
	 * WooCommerce integrations
	 *
	 * @param array $integrations
	 * @return array
	 */
	public function woocommerce_integrations( $integrations ) {
		//$integrations[] = 'Pronamic_Twinfield_WooCommerce_Integration';

		return $integrations;
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
		$billing_company   = $order->get_billing_company();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L802-L810
		$billing_full_name = $order->get_formatted_billing_full_name();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L498-L506
		$billing_address_1 = $order->get_billing_address_1();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L508-L516
		$billing_address_2 = $order->get_billing_address_2();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L538-L546
		$billing_postcode  = $order->get_billing_postcode();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L518-L526
		$biliing_city      = $order->get_billing_city();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L548-L556
		$billing_country   = $order->get_billing_country();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L558-L566
		$billing_email     = $order->get_billing_email();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L568-L576
		$billing_phone     = $order->get_billing_phone();

		// Shipping.

		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L598-L606
		$shipping_company   = $order->get_shipping_company();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L802-L810
		$shipping_full_name = $order->get_formatted_shipping_full_name();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L608-L616
		$shipping_address_1 = $order->get_shipping_address_1();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L618-L626
		$shipping_address_2 = $order->get_shipping_address_2();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L648-L656
		$shipping_postcode  = $order->get_shipping_postcode();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L628-L636
		$shipping_city      = $order->get_shipping_city();
		// @see https://github.com/woocommerce/woocommerce/blob/3.3.4/includes/class-wc-order.php#L548-L556
		$shipping_country   = $order->get_shipping_country();

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

	/**
	 * Twinfield post customer
	 *
	 * @param SalesInvoice $invoice
	 * @param int          $post_id
	 */
	public function twinfield_post_sales_invoice( $invoice, $post_id ) {
		return $invoice;

		if ( 'shop_order' === get_post_type( $post_id ) ) {
			// Integration
			$twinfield_integration = WC()->integrations->integrations['twinfield'];

			$twinfield_default_article_code    = get_option( 'twinfield_default_article_code' );
			$twinfield_default_subarticle_code = get_option( 'twinfield_default_subarticle_code' );

			// Order
			$order = wc_get_order( $post_id );

			// Items
			// @see https://github.com/woothemes/woocommerce/blob/2.5.3/includes/abstracts/abstract-wc-order.php#L1118-L1150
			foreach ( $order->get_items() as $item ) {
				$line = $invoice->new_line();

				// Find and article and subarticle id if set
				$article_code    = get_post_meta( $item['product_id'], '_twinfield_article_code', true );
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
				$line->set_value_excl( $order->get_item_total( $item, false, false ) );
				$line->set_vat_code( $twinfield_integration->get_tax_class_vat_code( $item['tax_class'] ) );
				$line->set_free_text_1( $item['name'] );
			}

			// Fees
			// @see https://github.com/woothemes/woocommerce/blob/2.5.3/includes/abstracts/abstract-wc-order.php#L1221-L1228
			foreach ( $order->get_fees() as $item ) {
				$line = $invoice->new_line();

				$line->set_article( $twinfield_default_article_code );
				$line->set_subarticle( $twinfield_default_subarticle_code );
				$line->set_quantity( 1 );
				$line->set_value_excl( $order->get_item_total( $item, false, false ) );
				$line->set_free_text_1( __( 'Fee', 'twinfield_woocommerce' ) );
			}

			// Shipping
			// @see https://github.com/woothemes/woocommerce/blob/2.5.3/includes/abstracts/abstract-wc-order.php#L1239-L1246
			foreach ( $order->get_shipping_methods() as $item ) {
				$line = $invoice->new_line();

				$line->set_article( $twinfield_integration->get_shipping_method_article_code( $item['method_id'] ) );
				$line->set_subarticle( $twinfield_integration->get_shipping_method_subarticle_code( $item['method_id'] ) );
				$line->set_quantity( 1 );
				$line->set_value_excl( $item['cost'] );
				$line->set_vat_code( $twinfield_integration->get_tax_class_vat_code( get_option( 'woocommerce_shipping_tax_class' ) ) );
				$line->set_free_text_1( $item['name'] );
			}
		}

		return $invoice;
	}
}