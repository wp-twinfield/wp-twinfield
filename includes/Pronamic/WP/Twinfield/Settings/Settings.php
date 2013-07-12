<?php

namespace Pronamic\WP\Twinfield\Settings;

class Settings {
		
	public function register_settings() {
		
		/**
		 * ======
		 * API Section
		 * ======
		 */
		
		add_settings_section(
			'api',
			__( 'API Settings', 'twinfield' ),
			array( $this, 'section_view' ),
			'twinfield-settings'
		);
		
		add_settings_field(
			'twinfield_username',
			__( 'Username', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_username' )
		);
		
		add_settings_field(
			'twinfield_password',
			__( 'Password', 'twinfield' ),
			array( $this, 'render_password' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_password' )
		);
		
		add_settings_field(
			'twinfield_organisation',
			__( 'Organisation', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_organisation' )
		);
		
		add_settings_field(
			'twinfield_office_code',
			__( 'Office Code', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'api',
			array( 'label_for' => 'twinfield_office_code' )
		);
		
		// Registered settings for the api section
		register_setting( 'wp_twinfield_api', 'twinfield_username' );
		register_setting( 'wp_twinfield_api', 'twinfield_password' );
		register_setting( 'wp_twinfield_api', 'twinfield_organisation' );
		register_setting( 'wp_twinfield_api', 'twinfield_office_code' );
		
		/**
		 * =========
		 * Permalinks Section
		 * ==========
		 */
		
		add_settings_section(
			'permalinks',
			__( 'Permalink Settings', 'twinfield' ),
			array( $this, 'section_view' ),
			'twinfield-settings'
		);
		
		add_settings_field(
			'wp_twinfield_invoice_slug',
			__( 'Invoice Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'permalinks',
			array( 'label_for' => 'wp_twinfield_invoice_slug' )
		);
		
		add_settings_field(
			'wp_twinfield_customer_slug',
			__( 'Customer Slug', 'twinfield' ),
			array( $this, 'render_text' ),
			'twinfield-settings',
			'permalinks',
			array( 'label_for' => 'wp_twinfield_customer_slug' )
		);
		
		// Settings for the permalinks section
		register_setting( 'wp_twinfield_permalinks', 'wp_twinfield_invoice_slug' );
		register_setting( 'wp_twinfield_permalinks', 'wp_twinfield_customer_slug' );
	}
	
	public function section_view() {}
	
	public function render_text( $attributes ) {
		?>
		<input id="<?php echo $attributes['label_for']; ?>" type="text" name="<?php echo $attributes['label_for']; ?>" value="<?php echo get_option( $attributes['label_for'] ); ?>"/>
		<?php if ( isset( $attributes['description'] ) ) : ?><span class="description"><?php echo $attributes['description']; ?></span><?php endif; ?>
			<?php
	}
	
	public function render_password( $attributes ) {
		?>
		<input id="<?php echo $attributes['label_for']; ?>" type="password" name="<?php echo $attributes['label_for']; ?>" value="<?php echo get_option( $attributes['label_for'] ); ?>"/>
		<?php if ( isset( $attributes['description'] ) ) : ?><span class="description"><?php echo $attributes['description']; ?></span><?php endif; ?>
		<?php
	}
	
	public static function tab_html( $section, $default = false ) {
		
		printf( 
			"<a class='nav-tab %s' href='%s'>%s</a>",
			( self::is_active_tab( $section ) || ( ! filter_has_var( INPUT_GET, 'tab' ) && 'api' === $section ) ) ? 'nav-tab-active' : '',
			add_query_arg( array( 'page' => 'twinfield-settings', 'tab' => $section ), admin_url( 'admin.php' ) ),
			ucfirst( $section )
		);
		
	}
	
	public static function is_active_tab( $section ) {
		return ( $section === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) );
	}
	
	public static function active_tab_group() {
		$tab = self::active_tab();
		
		return 'wp_twinfield_' . $tab ;
	}
	
	public static function active_tab() {
		return ( $tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) ) ? $tab : 'api';
	}
	
}