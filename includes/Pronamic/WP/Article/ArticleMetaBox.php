<?php

namespace Pronamic\WP\Article;

/**
 * ArticleMetaBox
 *
 * Used to display the metabox and determine which
 * post type the metabox should show on.
 *
 * To get the metabox to show on your post, just add
 * 'twinfield_article' to the supports parameter.
 *
 * Actions used:
 * ----------------
 *
 * add_meta_boxes
 * save_post
 *
 * ----------------
 *
 * @since 0.0.1
 *
 * @uses \ZFramework\Base\View()
 *
 * @package Pronamic\WP
 * @subpackage Article
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
use \ZFramework\Base\View;

class ArticleMetaBox {
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Determines where to show the meta box. It will
	 * get all post types, loop through and check if the
	 * post type supports 'twinfield_article'
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_boxes() {
		$post_types = get_post_types( '', 'names' );

		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'twinfield_article' ) ) {
				add_meta_box(
						'pronamic_twinfield_article',
						__( 'Twinfield Article', 'twinfield' ),
						array( $this, 'view' ),
						$post_type,
						'normal',
						'high'
				);
			}
		}
	}

	/**
	 * Displays the metabox contents
	 *
	 * Loads the view file from ~/views/Pronamic/WP/Article/articlemetabox_view
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param WP_Post $post
	 * @return void
	 */
	public function view( $post ) {
		// Get current id information
		$twinfield_article = get_post_meta( $post->ID, 'twinfield_article', true );

		// Get the article and subarticle ids
		$twinfield_article_id = ( isset( $twinfield_article['article_id'] ) ? $twinfield_article['article_id'] : '' );
		$twinfield_subarticle_id = ( isset( $twinfield_article['subarticle_id'] ) ? $twinfield_article['subarticle_id'] : '' );

		// Generate the nonce field
		$nonce = wp_nonce_field( 'twinfield_article', 'twinfield_article_nonce', true, false );

		// Make the view
		$view = new View( dirname( \Twinfield::$file ) . '/views/Pronamic/WP/Article' );
		$view
			->setVariable( 'nonce', $nonce )
			->setVariable( 'twinfield_article_id', $twinfield_article_id )
			->setVariable( 'twinfield_subarticle_id', $twinfield_subarticle_id )
			->setView( 'articlemetabox_view' )
			->render();
	}

	/**
	 * Saves the article meta box information
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 * @param string $post_id
	 * @param WP_Post $post
	 * @return void
	 */
	public function save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! isset( $_POST['twinfield_article_nonce'] ) )
			return;

		if ( ! current_user_can( 'edit_post' ) )
			return;

		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'twinfield_article_nonce'), 'twinfield_article' ) )
			return;

		if ( ! post_type_supports( $post->post_type, 'twinfield_article' ) )
			return;

		$twinfield_article_id = filter_input( INPUT_POST, 'twinfield_article_id', FILTER_VALIDATE_INT );
		$twinfield_subarticle_id = filter_input( INPUT_POST, 'twinfield_subarticle_id', FILTER_VALIDATE_INT );

		// Updates the post meta
		if ( isset( $twinfield_article_id ) ) {
			update_post_meta( $post_id, 'twinfield_article', array(
				'article_id' => $twinfield_article_id,
				'subarticle_id' => $twinfield_subarticle_id
			) );
		} else {
			delete_post_meta( $post_id, 'twinfield_article' );
		}

	}
}