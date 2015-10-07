<?php

namespace Pronamic\WP\Twinfield\Plugin;

class ArticlesAdmin {
	/**
	 * Twinfield plugin object.
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Twinfield plugin admin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Meta box.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Save post.
		add_action( 'save_post', array( $this, 'save_post' ) );

		// Columns.
		add_filter( 'manage_posts_columns' , array( $this, 'manage_posts_columns' ), 10, 2 );

		add_action( 'manage_posts_custom_column' , array( $this, 'manage_posts_custom_column' ), 10, 2 );
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_article' ) ) {
			add_meta_box(
				'pronamic_twinfield_article',
				__( 'Twinfield Article', 'twinfield' ),
				array( $this, 'article_meta_box' ),
				$post_type,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Article meta box.
	 */
	public function article_meta_box( $post ) {
		wp_nonce_field( 'twinfield_article', 'twinfield_article_nonce' );

		$twinfield_article_code    = get_post_meta( $post->ID, '_twinfield_article_code', true );
		$twinfield_subarticle_code = get_post_meta( $post->ID, '_twinfield_subarticle_code', true );

		include plugin_dir_path( $this->plugin->file ) . 'admin/meta-box-article.php';
	}

	/**
	 * Save post.
	 */
	public function save_post( $post_id ) {
		if ( ! filter_has_var( INPUT_POST, 'twinfield_article_nonce' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'twinfield_article_nonce' ), 'twinfield_article' ) ) {
			return;
		}

		if ( ! post_type_supports( get_post_type( $post_id ), 'twinfield_article' ) ) {
			return;
		}

		$twinfield_article_code = filter_input( INPUT_POST, 'twinfield_article_code', FILTER_SANITIZE_STRING );
		if ( empty( $twinfield_article_code ) ) {
			delete_post_meta( $post_id, '_twinfield_article_code' );
		} else {
			update_post_meta( $post_id, '_twinfield_article_code', $twinfield_article_code );
		}

		$twinfield_subarticle_code = filter_input( INPUT_POST, 'twinfield_subarticle_code', FILTER_SANITIZE_STRING );
		if ( empty( $twinfield_subarticle_code ) ) {
			delete_post_meta( $post_id, '_twinfield_subarticle_code' );
		} else {
			update_post_meta( $post_id, '_twinfield_subarticle_code', $twinfield_subarticle_code );
		}
	}

	/**
	 * Manage posts columns
	 *
	 * @param array  $posts_columns
	 * @param string $post_type
	 */
	public function manage_posts_columns( $columns, $post_type ) {
		if ( post_type_supports( $post_type, 'twinfield_article' ) ) {
			$columns['twinfield_article'] = __( 'Twinfield', 'twinfield' );

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
}
