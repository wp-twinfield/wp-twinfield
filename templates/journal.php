<?php

namespace Pronamic\WP\Twinfield\Plugin;

global $twinfield_journal_template;

if ( ! \is_object( $twinfield_journal_template ) ) {
	return;
}

\get_header();

?>
<pre><?php \var_dump( $twinfield_journal_template ); ?></pre>
<?php

\get_footer();
