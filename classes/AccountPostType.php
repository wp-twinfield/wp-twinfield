<?php

namespace Pronamic\WP\Twinfield\Plugin;

/**
 * Twinfield post type.
 */
class AccountPostType {
	const NAME = 'twinfield_account';

	/**
	 * Construct.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_filter( 'manage_edit-' . self::NAME . '_columns', array( $this, 'columns' ) );

		add_action( 'manage_' . self::NAME . '_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
	}

	/**
	 * Init.
	 */
	public function init() {
		register_post_type(
			self::NAME, array(
				'label'        => __( 'Twinfield Accounts', 'twinfield' ),
				'labels'       => array(
					'name'               => _x( 'Twinfield Accounts', 'twinfield', 'twinfield' ),
					'singular_name'      => _x( 'Twinfield Account', 'twinfield', 'twinfield' ),
					'menu_name'          => _x( 'Twinfield', 'admin menu', 'twinfield' ),
					'name_admin_bar'     => _x( 'Twinfield Account', 'twinfield', 'twinfield' ),
					'add_new'            => _x( 'Add New', 'twinfield', 'twinfield' ),
					'add_new_item'       => _x( 'Add New Twinfield Account', 'twinfield', 'twinfield' ),
					'new_item'           => _x( 'New Twinfield Account', 'twinfield', 'twinfield' ),
					'edit_item'          => _x( 'Edit Twinfield Account', 'twinfield', 'twinfield' ),
					'view_item'          => _x( 'View Twinfield Account', 'twinfield', 'twinfield' ),
					'all_items'          => _x( 'All Twinfield Accounts', 'twinfield', 'twinfield' ),
					'search_items'       => _x( 'Search Twinfield Accounts', 'twinfield', 'twinfield' ),
					'parent_item_colon'  => _x( 'Parent Twinfield Account:', 'twinfield', 'twinfield' ),
					'not_found'          => _x( 'No Twinfield accounts found.', 'twinfield', 'twinfield' ),
					'not_found_in_trash' => _x( 'No Twinfield accounts found in Trash.', 'twinfield', 'twinfield' ),
				),
				'public'       => true,
				'menu_icon'    => 'dashicons-lock',
				'hierarchical' => true,
				'supports'     => array(
					'title',
					'author',
					'revisions',
					'twinfield-account',
				),
				'has_archive'  => true,
				'rewrite'      => array(
					'slug'       => _x( 'twinfield/accounts', 'slug', 'twinfield' ),
					'with_front' => false,
				),
			)
		);
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( ! post_type_supports( $post_type, 'twinfield-account' ) ) {
			return;
		}

		add_meta_box(
			'twinfield_open_authorization',
			__( 'Open Authorization', 'twinfield' ),
			array( $this, 'meta_box_open_authorization' ),
			$post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Meta box open authorization.
	 */
	public function meta_box_open_authorization( $post ) {
		include plugin_dir_path( $this->plugin->file ) . 'admin/meta-box-account-open-authorization.php';
	}

	/**
	 * Columns.
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = array(
			'cb'                     => '<input type="checkbox" />',
			'title'                  => __( 'Title', 'twinfield' ),
			'twinfield_username'     => __( 'Username', 'twinfield' ),
			'twinfield_organisation' => __( 'Organisation', 'twinfield' ),
			'author'                 => __( 'Author', 'twinfield' ),
			'date'                   => __( 'Date', 'twinfield' ),
		);

		return $columns;
	}

	/**
	 * Custom column.
	 *
	 * @param string $column
	 * @param int $post_id
	 */
	public function custom_columns( $column, $post_id ) {
		$username     = get_post_meta( $post_id, '_twinfield_username', true );
		$organisation = get_post_meta( $post_id, '_twinfield_organisation', true );

		switch ( $column ) {
			case 'twinfield_username':
				echo esc_html( $username );

				break;
			case 'twinfield_organisation':
				echo esc_html( $organisation );

				break;
		}
	}
}
