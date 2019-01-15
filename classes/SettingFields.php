<?php

namespace Pronamic\WP\Twinfield\Plugin;

class SettingFields {
	/**
	 * Array to HTML attributes
	 *
	 * @param array $pieces
	 */
	private static function array_to_html_attributes( array $attributes ) {
		$html  = '';
		$space = '';

		foreach ( $attributes as $key => $value ) {
			$html .= $space . $key . '="' . esc_attr( $value ) . '"';

			$space = ' ';
		}

		return $html;
	}

	/**
	 * Render text
	 *
	 * @param array $attributes
	 */
	public static function render_text( $attributes ) {
		$attributes = wp_parse_args(
			$attributes,
			array(
				'id'      => '',
				'type'    => 'text',
				'name'    => '',
				'value'   => '',
				'classes' => array( 'regular-text' ),
			)
		);

		if ( isset( $attributes['label_for'] ) ) {
			$attributes['id']    = $attributes['label_for'];
			$attributes['name']  = $attributes['label_for'];
			$attributes['value'] = get_option( $attributes['label_for'] );

			unset( $attributes['label_for'] );
		}

		if ( isset( $attributes['classes'] ) ) {
			$attributes['class'] = implode( ' ', $attributes['classes'] );

			unset( $attributes['classes'] );
		}

		$description = null;
		if ( isset( $attributes['description'] ) ) {
			$description = $attributes['description'];

			unset( $attributes['description'] );
		}

		printf( '<input %s />', self::array_to_html_attributes( $attributes ) ); // xss ok

		if ( $description ) {
			printf( // xss ok
				'<span class="description"><br />%s</span>',
				$description
			); // xss ok
		}
	}

	/**
	 * Render password
	 *
	 * @param array $attributes
	 */
	public static function render_password( $attributes ) {
		$attributes['type'] = 'password';

		self::render_text( $attributes );
	}

	public static function radio_buttons( $args ) {
		$name    = $args['label_for'];
		$current = get_option( $name );
		$options = $args['options'];

		echo '<fieldset>';

		printf(
			'<legend class="screen-reader-text"><span>%s</span></legend>',
			'Test'
		);

		foreach ( $options as $value => $label ) {
			echo '<label>';

			printf(
				'<input type="radio" name="%s" value="%s" %s> %s',
				esc_attr( $name ),
				esc_attr( $value ),
				checked( $current, $value, false ),
				esc_html( $label )
			);

			echo '</label>';

			echo '<br />';
		}

		echo '</fieldset>';
	}
}
