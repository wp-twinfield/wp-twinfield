<?php

if ( empty( $this->plugin->openid_connect_provider ) ) {
	esc_html_e( 'Please configure OpenID settings.', 'twinfield' );
}

$openid_connect_provider = $this->plugin->openid_connect_provider;

$label = __( 'Connect with Twinfield', 'twinfield' );

$state = (object) array(
	'post_id'      => get_the_ID(),
	'redirect_uri' => get_edit_post_link( $post->ID, '' ),
);

$url = $openid_connect_provider->get_authorize_url( $state );

printf(
	'<a href="%s">%s</a>',
	esc_url( $url ),
	esc_html( $label )
);
