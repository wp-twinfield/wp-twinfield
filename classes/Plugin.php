<?php

namespace Pronamic\WP\Twinfield\Plugin;

use Pronamic\WP\Twinfield\Credentials;
use Pronamic\WP\Twinfield\Client;
use Pronamic\WP\Twinfield\Finder;
use Pronamic\WP\Twinfield\XMLProcessor;
use Pronamic\WP\Twinfield\Authentication\OpenIdConnectProvider;
use Pronamic\WP\Twinfield\Customers\Customer;
use Pronamic\WP\Twinfield\SalesInvoices\SalesInvoice;
use Pronamic\WP\Twinfield\SalesInvoices\SalesInvoiceStatus;

class Plugin {
	/**
	 * Plugin file
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Plugin dir path
	 *
	 * @var string
	 */
	public $dir_path;

	/**
	 * Version.
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Constructs and initialize Pronamic WordPress Extensions plugin
	 *
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->file     = $file;
		$this->dir_path = plugin_dir_path( $file );

		// Includes.
		include_once $this->dir_path . '/includes/functions.php';
		include_once $this->dir_path . '/includes/template.php';

		// Actions.
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 10, 9 );

		// Admin.
		if ( is_admin() ) {
			$this->admin = new Admin( $this );
		}

		// Other.
		$this->account_post_type = new AccountPostType( $this );
		$this->rest_api          = new RestApi( $this );
		$this->invoices_public   = new InvoicesPublic( $this );
		$this->customers_public  = new CustomersPublic( $this );

		// Extensions.
		$this->woocommerce_extension = new WooCommerceExtension( $this );

		// OpenID Connect Provider.
		if ( 'openid_connect' === get_option( 'twinfield_authorization_method' ) ) {
			$client_id     = get_option( 'twinfield_openid_connect_client_id' );
			$client_secret = get_option( 'twinfield_openid_connect_client_secret' );
			$redirect_uri  = get_option( 'twinfield_openid_connect_redirect_uri' );

			if ( $client_id && $client_secret && $redirect_uri ) {
				$this->openid_connect_provider = new OpenIdConnectProvider( $client_id, $client_secret, $redirect_uri );

				add_action( 'init', array( $this, 'maybe_handle_twinfield_oauth' ) );
			}
		}

		// Setup.
		$this->account_post_type->setup();
	}

	/**
	 * Initialize.
	 */
	public function init() {
		// Tables
		$this->register_table( 'twinfield_offices' );
		$this->register_table( 'twinfield_users' );
		$this->register_table( 'twinfield_dimensions' );
		$this->register_table( 'twinfield_general_journals' );
		$this->register_table( 'twinfield_transactions' );
		$this->register_table( 'twinfield_transaction_lines' );
		$this->register_table( 'twinfield_customers' );
		$this->register_table( 'twinfield_suppliers' );

		// Install
		$this->maybe_install();
	}

	public function maybe_install() {
		if ( get_option( 'twinfield_vesion' ) !== $this->version ) {
			$this->install();

			update_option( 'twinfield_vesion', $this->version );
		}
	}

	private function register_table( $name ) {
		global $wpdb;

		$wpdb->$name = $wpdb->prefix . $name;
	}

	private function install_table( $name, $columns ) {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$full_table_name = $wpdb->$name;

		$charset_collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if ( ! empty( $wpdb->collate ) ) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}

		$table_options = $charset_collate;

		dbDelta( "CREATE TABLE $full_table_name ( $columns ) $table_options" );
	}

	/**
	 * Install.
	 */
	public function install() {
		$this->install_table(
			'twinfield_offices', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			code VARCHAR(8) NOT NULL,
			name VARCHAR(64) DEFAULT NULL,
			post_id BIGINT(20) UNSIGNED DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (code),
			UNIQUE KEY post_id (post_id)
		'
		);

		$this->install_table(
			'twinfield_users', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			office_id BIGINT(20) UNSIGNED DEFAULT NULL,
			code VARCHAR(16) NOT NULL,
			name VARCHAR(64) DEFAULT NULL,
			shortname VARCHAR(64) DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (office_id, code)
		'
		);

		$this->install_table(
			'twinfield_dimensions', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			office_id BIGINT(20) UNSIGNED DEFAULT NULL,
			type_id BIGINT(20) UNSIGNED DEFAULT NULL,
			code VARCHAR(16) NOT NULL,
			name VARCHAR(64) DEFAULT NULL,
			type VARCHAR(16) NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (office_id, type_id, code)
		'
		);

		$this->install_table(
			'twinfield_general_journals', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			office_id BIGINT(20) UNSIGNED NOT NULL,
			code VARCHAR(16) NOT NULL,
			name VARCHAR(64) DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (office_id, code)
		'
		);

		$this->install_table(
			'twinfield_transactions', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			general_journal_id BIGINT(20) UNSIGNED NOT NULL,
			number VARCHAR(16) NOT NULL COMMENT "Twinfield browse column `fin.trs.head.number` or XML element `browse > tr > key > number`.",
			status VARCHAR(20) DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.status`.",
			year SMALLINT(4) UNSIGNED DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.year`.",
			period SMALLINT(2) UNSIGNED DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.period`.",
			year_period MEDIUMINT(6) UNSIGNED DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.yearperiod`.",
			date DATE DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.date`.",
			currency_code VARCHAR(3) DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.curcode`.",
			regime VARCHAR(16) DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > regime`.",
			relation_code VARCHAR(16) DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.relation`.",
			input_date DATETIME DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.inpdate`.",
			username VARCHAR(16) DEFAULT NULL COMMENT "Twinfield browse column `fin.trs.head.username`.",
			origin_reference VARCHAR(16) DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > originreference`.",
			modification_date DATETIME DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > modificationdate`.",
			due_date DATE DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > duedate`.",
			invoice_number VARCHAR(16) DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > invoicenumber`.",
			free_text_1 VARCHAR(64) DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > freetext1`.",
			free_text_2 VARCHAR(64) DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > freetext2`.",
			free_text_3 VARCHAR(64) DEFAULT NULL COMMENT "Twinfield transaction XML `transaction > header > freetext3`.",
			deletion_date DATETIME DEFAULT NULL COMMENT "Twinfield deletion date from deleted transactions webservice.",
			deletion_reason TEXT DEFAULT NULL COMMENT "Twinfield deletion reason from deleted transactions webservice.",
			deletion_user VARCHAR(200) DEFAULT NULL COMMENT "Twinfield deletion user from deleted transactions webservice.",
			browse_code_030_2 BOOL DEFAULT NULL COMMENT "Flag to indicate a transaction is retrieved from Twinfield browse code `030_2`.",
			browse_code_100 BOOL DEFAULT NULL COMMENT "Flag to indicate a transaction is retrieved from Twinfield browse code `100`.",
			browse_code_200 BOOL DEFAULT NULL COMMENT "Flag to indicate a transaction is retrieved from Twinfield browse code `200`.",
			PRIMARY KEY  (id),
			UNIQUE KEY number (general_journal_id, number),
			KEY `date` (`date`)
		'
		);

		$this->install_table(
			'twinfield_transaction_lines', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			transaction_id BIGINT(20) UNSIGNED NOT NULL,
			line VARCHAR(16) NOTT NULL,
			dimension_1_id BIGINT(20) UNSIGNED DEFAULT NULL,
			dimension_2_id BIGINT(20) UNSIGNED DEFAULT NULL,
			dimension_3_id BIGINT(20) UNSIGNED DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY line (transaction_id, line)
		'
		);

		$this->install_table(
			'twinfield_customers', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			office_id BIGINT(20) UNSIGNED NOT NULL,
			code VARCHAR(16) NOT NULL,
			name VARCHAR(64) DEFAULT NULL,
			shortname VARCHAR(64) DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (office_id, code)
		'
		);

		$this->install_table(
			'twinfield_suppliers', '
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			updated_at DATETIME DEFAULT NULL,
			office_id BIGINT(20) UNSIGNED NOT NULL,
			code VARCHAR(16) NOT NULL,
			name VARCHAR(64) DEFAULT NULL,
			shortname VARCHAR(64) DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY code (office_id, code)
		'
		);

		$this->install_table(
			'twinfield_declarations', '
			id BIGINT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME DEFAULT NULL,
			office_id BIGINT(20) UNSIGNED NOT NULL,
			document_id VARCHAR(8) DEFAULT NULL,
			document_code VARCHAR(16) DEFAULT NULL,
			document_name VARCHAR(200) DEFAULT NULL,
			time_frame_year VARCHAR(4) DEFAULT NULL,
			time_frame_period VARCHAR(200) DEFAULT NULL,
			status_description VARCHAR(200) DEFAULT NULL,
			status_step_index VARCHAR(16) DEFAULT NULL,
			status_extra_information VARCHAR(200) DEFAULT NULL,
			assignee_code VARCHAR(200) DEFAULT NULL,
			assignee_name VARCHAR(200) DEFAULT NULL,
			company_code VARCHAR(16) DEFAULT NULL,
			company_name VARCHAR(200) DEFAULT NULL,
			payment_reference VARCHAR(200) DEFAULT NULL,
			vat_return_xbrl TEXT DEFAULT NULL,
			period_start_date DATE DEFAULT NULL,
			period_end_date DATE DEFAULT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY document_id (office_id, document_id),
			KEY period_start_date (period_start_date),
			KEY period_end_date (period_end_date)
		'
		);

		/**
		 * Convert.
		 *
		 * @link https://developer.wordpress.org/reference/functions/maybe_convert_table_to_utf8mb4/
		 */
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_offices );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_users );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_dimensions );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_general_journals );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_transactions );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_transaction_lines );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_customers );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_suppliers );
		maybe_convert_table_to_utf8mb4( $wpdb->twinfield_declarations );
	}

	/**
	 * Maybe handle Twinfield OpenID Authorization.
	 */
	public function maybe_handle_twinfield_oauth() {
		if ( empty( $this->openid_connect_provider ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'code' ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'state' ) ) {
			return;
		}

		if ( ! filter_has_var( INPUT_GET, 'session_state' ) ) {
			return;
		}

		$code          = filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING );
		$state         = filter_input( INPUT_GET, 'state', FILTER_SANITIZE_STRING );
		$session_state = filter_input( INPUT_GET, 'session_state', FILTER_SANITIZE_STRING );

		$data = $this->openid_connect_provider->get_access_token( $code );

		$state_data = json_decode( base64_decode( $state ) );

		if ( is_object( $state_data ) && isset( $state_data->post_id ) ) {
			$this->set_access_token( $state_data->post_id, $data );
		} else {
			$this->set_access_token( $data );
		}

		$url = add_query_arg(
			array(
				'code'          => false,
				'state'         => false,
				'session_state' => false,
			)
		);

		wp_redirect( $url );

		exit;
	}

	/**
	 * Get URL prefix.
	 *
	 * @see https://github.com/WP-API/api-core/blob/develop/wp-includes/rest-api/rest-functions.php#L204-L220
	 * @return string
	 */
	public function get_url_prefix() {
		return 'twinfield';
	}

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		// Load text domain.
		load_plugin_textdomain( 'twinfield', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );

		// Bootstrap.
		do_action( 'twinfield_bootstrap' );
	}

	/**
	 * Plugins URL
	 *
	 * @param string $path
	 */
	public function plugins_url( $path ) {
		return plugins_url( $path, $this->file );
	}

	public function get_access_token() {
		$access_token  = get_option( 'twinfield_openid_connect_access_token' );
		$refresh_token = get_option( 'twinfield_openid_connect_refresh_token' );
		$expiration    = get_option( 'twinfield_openid_connect_expiration' );

		if ( $expiration > time() ) {
			return $access_token;
		}

		if ( empty( $this->openid_connect_provider ) ) {
			return false;
		}

		if ( empty( $refresh_token ) ) {
			return false;
		}

		$data = $this->openid_connect_provider->refresh_token( $refresh_token );

		return $this->set_access_token( $data );
	}

	public function set_access_token( $data ) {
		if ( is_object( $data ) ) {
			if ( isset( $data->id_token ) ) {
				update_option( 'twinfield_openid_connect_id_token', $data->id_token );
			}

			if ( isset( $data->access_token ) ) {
				update_option( 'twinfield_openid_connect_access_token', $data->access_token );
			}

			if ( isset( $data->expires_in ) ) {
				update_option( 'twinfield_openid_connect_expires_in', $data->expires_in );
			}

			if ( isset( $data->token_type ) ) {
				update_option( 'twinfield_openid_connect_token_type', $data->token_type );
			}

			if ( isset( $data->refresh_token ) ) {
				update_option( 'twinfield_openid_connect_refresh_token', $data->refresh_token );
			}
		}

		$access_token = get_option( 'twinfield_openid_connect_access_token' );

		$validation = $this->openid_connect_provider->get_access_token_validation( $access_token );

		if ( is_object( $validation ) ) {
			if ( isset( $validation->exp ) ) {
				update_option( 'twinfield_openid_connect_expiration', $validation->exp );
			}

			if ( isset( $validation->nbf ) ) {
				update_option( 'twinfield_openid_connect_not_before_time', $validation->nbf );
			}

			if ( isset( $validation->{'twf.clusterUrl'} ) ) {
				update_option( 'twinfield_openid_connect_cluster', $validation->{'twf.clusterUrl'} );
			}
		}

		return $access_token;
	}

	private function get_authentication_strategy() {
		$method = get_option( 'twinfield_authorization_method' );

		switch ( $method ) {
			case 'openid_connect':
				$access_token = $this->get_access_token();
				$office       = get_option( 'twinfield_default_office_code' );
				$cluster      = get_option( 'twinfield_openid_connect_cluster' );

				$authentication_strategy = new \Pronamic\WP\Twinfield\Authentication\OpenIdConnectAuthenticationStrategy( $access_token, $office, $cluster );

				return $authentication_strategy;
			case 'web_services':
				$user         = get_option( 'twinfield_username' );
				$password     = get_option( 'twinfield_password' );
				$organisation = get_option( 'twinfield_organisation' );

				$credentials = new Credentials( $user, $password, $organisation );

				$authentication_strategy = new \Pronamic\WP\Twinfield\Authentication\WebServicesAuthenticationStrategy( $credentials );

				return $authentication_strategy;
		}
	}

	public function get_client() {
		$authentication_strategy = $this->get_authentication_strategy();

		if ( empty( $authentication_strategy ) ) {
			return false;
		}

		$client = new Client( $authentication_strategy );

		$client->login();

		return $client;
	}

	/**
	 * Get XML processor
	 */
	public function get_xml_processor() {
		return $this->get_client()->get_xml_processor();
	}

	/**
	 * Get finder
	 */
	public function get_finder() {
		return $this->get_client()->get_finder();
	}

	public function get_twinfield_customer_id_from_post( $post_id ) {
		$customer_id = get_post_meta( $post_id, '_twinfield_customer_id', true );

		$customer_id = apply_filters( 'twinfield_post_customer_id', $customer_id, $post_id );

		return $customer_id;
	}

	public function get_twinfield_customer_from_post( $post_id ) {
		$twinfield_customer_id = $this->get_twinfield_customer_id_from_post( $post_id );

		$customer = new Customer();
		$customer->set_office( get_option( 'twinfield_default_office_code' ) );
		$customer->set_code( empty( $twinfield_customer_id ) ? null : $twinfield_customer_id );

		$financials = $customer->get_financials();
		$financials->set_due_days( 14 );

		$credit_management = $customer->get_credit_management();
		$credit_management->set_send_reminder( 'email' );

		$customer = apply_filters( 'twinfield_post_customer', $customer, $post_id );

		return $customer;
	}

	/**
	 * Get sales invoice by post ID
	 *
	 * @param int $post_id
	 * @return SalesInvoice
	 */
	public function get_twinfield_sales_invoice_from_post( $post_id ) {
		$invoice_number = get_post_meta( $post_id, '_twinfield_invoice_number', true );
		$response       = get_post_meta( $post_id, '_twinfield_response', true );

		$customer = $this->get_twinfield_customer_from_post( $post_id );

		$invoice = new SalesInvoice();

		$header = $invoice->get_header();

		$header->set_office( get_option( 'twinfield_default_office_code' ) );
		$header->set_type( get_option( 'twinfield_default_invoice_type' ) );
		$header->set_customer( $customer->get_code() );
		$header->set_status( SalesInvoiceStatus::STATUS_CONCEPT );
		$header->set_footer_text(
			sprintf(
				/* translators: %s: Date */
				__( 'Invoice created by WordPress on %s.', 'twinfield' ),
				date_i18n( 'D j M Y @ H:i' )
			)
		);

		$invoice = apply_filters( 'twinfield_post_sales_invoice', $invoice, $post_id );

		return $invoice;
	}

	/**
	 * Insert Twinfield sales invoice from post.
	 *
	 * @param int $post_id Post ID.
	 */
	public function insert_twinfield_sales_invoice_from_post( $post_id ) {
		$invoice_number = get_post_meta( $post_id, '_twinfield_invoice_number', true );

		if ( ! empty( $invoice_number ) ) {
			return;
		}

		$sales_invoice = $this->get_twinfield_sales_invoice_from_post( $post_id );

		$client = $this->get_client();

		$xml_processor = $client->get_xml_processor();

		$service = new \Pronamic\WP\Twinfield\SalesInvoices\SalesInvoiceService( $xml_processor );

		$response = $service->insert_sales_invoice( $sales_invoice );

		if ( $response ) {
			if ( $response->is_successful() ) {
				$sales_invoice = $response->get_sales_invoice();

				update_post_meta( $post_id, '_twinfield_invoice_number', $sales_invoice->get_header()->get_number() );

				delete_post_meta( $post_id, '_twinfield_invoice_response_xml' );
			} else {
				update_post_meta( $post_id, '_twinfield_invoice_response_xml', $response->get_message()->asXML() );
			}
		}

		return $sales_invoice;
	}
}
