<?php

namespace Pronamic\WP\Twinfield\Plugin;

global $twinfield_journals_template;

if ( ! \is_object( $twinfield_journals_template ) ) {
	return;
}

\get_header();

?>
<pre><?php \var_dump( $twinfield_journals_template ); ?></pre>
<?php

\get_footer();
