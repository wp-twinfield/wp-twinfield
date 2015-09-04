<?php

class Pronamic_WP_TwinfieldPlugin_Admin {
	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var self
	 */
	protected static $instance = null;

	//////////////////////////////////////////////////

	/**
	 * Extensions plugin
	 *
	 * @var Pronamic_WP_TwinfieldPlugin_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Twinfield plugin admin
	 */
	private function __construct( Pronamic_WP_TwinfieldPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Columns
		add_filter( 'manage_posts_columns' , array( $this, 'manage_posts_columns' ), 10, 2 );

		add_action( 'manage_posts_custom_column' , array( $this, 'manage_posts_custom_column' ), 10, 2 );
	}

	/**
	 * Manage posts columns
	 *
	 * @param array  $posts_columns
	 * @param string $post_type
	 */
	function manage_posts_columns( $columns, $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_article' ) ) {
			$columns['twinfield_article'] = __( 'Twinfield', 'orbis_subscriptions' );

			$new_columns = array();

			foreach ( $columns as $name => $label ) {
				if ( 'author' === $name ) {
					$new_columns['twinfield_article'] = $columns['twinfield_article'];
				}

				$new_columns[ $name ] = $label;
			}

			$columns = $new_columns;
		}

		return $columns;
	}

	function manage_posts_custom_column( $column_name, $post_id ) {
		if ( 'twinfield_article' === $column_name ) {
			$twinfield_article_code    = get_post_meta( $post_id, '_twinfield_article_code', true );
			$twinfield_subarticle_code = get_post_meta( $post_id, '_twinfield_subarticle_code', true );

			$items = array();

			if ( ! empty( $twinfield_article_code ) ) {
				$items[] = sprintf(
					'<strong>%s</strong>: %s',
					esc_html__( 'Article', 'twinfield' ),
					esc_html( $twinfield_article_code )
				);
			}

			if ( ! empty( $twinfield_article_code ) ) {
				$items[] = sprintf(
					'<strong>%s</strong>: %s',
					esc_html__( 'Subarticle', 'twinfield' ),
					esc_html( $twinfield_subarticle_code )
				);
			}

			echo implode( '<br />', $items );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		$this->settings = Pronamic_WP_TwinfieldPlugin_Settings::get_instance( $this->plugin );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Twinfield', 'twinfield' ),
			__( 'Twinfield', 'twinfield' ),
			'manage_options',
			'twinfield',
			array( $this, 'page_twinfield' ),
			$this->plugin->plugins_url( 'assets/admin/images/icon-16x16.png' )
		);

		/*
		add_submenu_page(
			'twinfield',
			_x( 'Twinfield Companies', 'twinfield.com', 'twinfield' ),
			_x( 'Companies', 'twinfield.com', 'twinfield' ),
			'manage_options',
			'twinfield_offices',
			array( $this, 'page_offices' )
		);
		*/

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Customers', 'twinfield' ),
			__( 'Customers', 'twinfield' ),
			'manage_options',
			'twinfield_customers',
			array( $this, 'page_customers' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Invoices', 'twinfield' ),
			__( 'Invoices', 'twinfield' ),
			'manage_options',
			'twinfield_invoices',
			array( $this, 'page_invoices' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Settings', 'twinfield' ),
			__( 'Settings', 'twinfield' ),
			'manage_options',
			'twinfield_settings',
			array( $this, 'page_settings' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Form Builder', 'twinfield' ),
			__( 'Form Builder', 'twinfield' ),
			'twinfield_form_builder',
			'twinfield_form_builder',
			array( $this, 'page_form_builder' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Merger Tool', 'twinfield' ),
			__( 'Merger Tool', 'twinfield' ),
			'twinfield_merger_tool',
			'twinfield_merger_tool',
			array( $this, 'page_merger_tool' )
		);

		add_submenu_page(
			'twinfield',
			__( 'Twinfield Documentation', 'twinfield' ),
			__( 'Documentation', 'twinfield' ),
			'manage_options',
			'twinfield_documentation',
			array( $this, 'page_documentation' )
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Admin enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		// Styles
		wp_register_style(
			'twinfield-admin',
			$this->plugin->plugins_url( 'assets/admin/css/twinfield_admin.css' ),
			array(),
			'1.0.0'
		);

		// Auto enqueued assets
		wp_enqueue_style( 'twinfield-admin' );
	}

	//////////////////////////////////////////////////

	/**
	 * Page
	 *
	 * @param string $id
	 */
	public function page( $id ) {
		$filename = 'views/page-' . $id . '.php';

		$this->plugin->display( $filename );
	}

	// Helper functions
	public function page_twinfield() { $this->page( 'twinfield' ); }
	public function page_offices() { $this->page( 'offices' ); }
	public function page_customers() { $this->page( 'customers' ); }
	public function page_invoices() { $this->page( 'invoices' ); }
	public function page_settings() { $this->page( 'settings' ); }
	public function page_form_builder() { $this->page( 'form_builder' ); }
	public function page_merger_tool() { $this->page( 'merger_tool' ); }
	public function page_documentation() { $this->page( 'documentation' ); }

	//////////////////////////////////////////////////

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance( Pronamic_WP_TwinfieldPlugin_Plugin $plugin ) {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self( $plugin );
		}

		return self::$instance;
	}
}
