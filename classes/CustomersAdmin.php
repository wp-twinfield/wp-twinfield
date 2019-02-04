<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Customers\Customer;
use Pronamic\WP\Twinfield\Customers\CustomerFinder;
use Pronamic\WP\Twinfield\SearchFields;

class CustomersAdmin {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

		/**
		 * Constructs and initialize Twinfield plugin admin
		 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Meta box
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Save post
		add_action( 'save_post', array( $this, 'save_post' ) );

		// Columns
		add_filter( 'manage_posts_columns', array( $this, 'manage_posts_columns' ), 10, 2 );

		add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );

		// AJAX
		add_action( 'wp_ajax_twinfield_search_customers', array( $this, 'ajax_twinfield_search_customers' ) );

		// User profile.
		add_action( 'show_user_profile', array( $this, 'user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'user_profile' ) );
		add_action( 'user_new_form', array( $this, 'user_profile' ) );

		add_action( 'personal_options_update', array( $this, 'user_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'user_update' ) );
		add_action( 'user_register', array( $this, 'user_update' ) );
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_customer' ) ) {
			add_meta_box(
				'pronamic_twinfield_article',
				__( 'Twinfield Customer', 'twinfield' ),
				array( $this, 'customer_meta_box' ),
				$post_type,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Article meta box.
	 */
	public function customer_meta_box( $post ) {
		wp_nonce_field( 'twinfield_customer', 'twinfield_customer_nonce' );

		$twinfield_customer_id = $this->plugin->get_twinfield_customer_id_from_post( $post->ID );

		$customer = $this->plugin->get_twinfield_customer_from_post( $post->ID );

		include plugin_dir_path( $this->plugin->file ) . 'admin/meta-box-customer.php';
	}

	/**
	 * Save post.
	 */
	public function save_post( $post_id ) {
		if ( ! filter_has_var( INPUT_POST, 'twinfield_customer_nonce' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'twinfield_customer_nonce' ), 'twinfield_customer' ) ) {
			return;
		}

		if ( ! post_type_supports( get_post_type( $post_id ), 'twinfield_customer' ) ) {
			return;
		}

		$twinfield_customer_id = filter_input( INPUT_POST, 'twinfield_customer_id', FILTER_SANITIZE_STRING );
		if ( empty( $twinfield_customer_id ) ) {
			delete_post_meta( $post_id, '_twinfield_customer_id' );
		} else {
			update_post_meta( $post_id, '_twinfield_customer_id', $twinfield_customer_id );
		}

		if ( filter_has_var( INPUT_POST, 'twinfield_create_customer' ) ) {
			$customer = $this->plugin->get_twinfield_customer_from_post( $post_id );

			$client = $this->plugin->get_client();

			$xml_processor = $client->get_xml_processor();

			$service = new \Pronamic\WP\Twinfield\Customers\CustomerService( $xml_processor );

			$response = $service->insert_customer( $customer );

			if ( $response ) {
				if ( $response->is_successful() ) {
					$customer = $response->get_customer();

					update_post_meta( $post_id, '_twinfield_customer_id', $customer->get_code() );

					delete_post_meta( $post_id, '_twinfield_customer_response_xml' );
				} else {
					update_post_meta( $post_id, '_twinfield_customer_response_xml', $response->get_message()->asXML() );
				}
			}
		}
	}

	/**
	 * Manage posts columns
	 *
	 * @param array  $posts_columns
	 * @param string $post_type
	 */
	public function manage_posts_columns( $columns, $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_customer' ) ) {
			$columns['twinfield_customer'] = __( 'Twinfield Customer', 'twinfield' );

			$new_columns = array();

			foreach ( $columns as $name => $label ) {
				if ( 'author' === $name ) {
					$new_columns['twinfield_customer'] = $columns['twinfield_customer'];
				}

				$new_columns[ $name ] = $label;
			}

			$columns = $new_columns;
		}

		return $columns;
	}

	public function manage_posts_custom_column( $column_name, $post_id ) {
		if ( 'twinfield_customer' === $column_name ) {
			$customer_id = get_post_meta( $post_id, '_twinfield_customer_id', true );

			if ( empty( $customer_id ) ) {
				echo esc_html( '—' );
			} else {
				echo esc_html( $customer_id );
			}
		}
	}

	/**
	 * AJAX Twinfield search customers
	 */
	public function ajax_twinfield_search_customers() {
		$result = array();

		$search = filter_input( INPUT_GET, 'search', FILTER_SANITIZE_STRING );

		if ( ! empty( $search ) ) {
			$finder = new CustomerFinder( $this->plugin->get_finder() );

			$customers = $finder->get_customers(
				$search,
				SearchFields::CODE_AND_NAME,
				1,
				10
			);

			foreach ( $customers as $customer ) {
				$object       = new \stdClass();
				$object->code = $customer->get_code();
				$object->name = $customer->get_name();

				$result[] = $object;
			}
		}

		wp_send_json( $result );

		exit;
	}

	/**
	 * User profile.
	 *
	 * @since 1.1.6
	 * @link https://github.com/WordPress/WordPress/blob/4.5.2/wp-admin/user-edit.php#L578-L600
	 */
	public function user_profile( $user ) {
		include dirname( __FILE__ ) . '/../admin/user-profile.php';
	}

	/**
	 * User update.
	 */
	public function user_update( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_POST, 'twinfield_customer_id' ) ) {
			return;
		}

		$customer_id = filter_input( INPUT_POST, 'twinfield_customer_id', FILTER_SANITIZE_STRING );

		update_user_meta( $user_id, 'twinfield_customer_id', $customer_id );
	}
}
