<?php
namespace Pronamic\WP\Twinfield\Invoice;

/**
 * Invoice MetaBox Factory.  Used to register a post type
 * with an associated class in use when the synchronization
 * is called.
 * 
 * All classes registered must have a method 'prepare_sync'
 * and the files that have the classes must have been loaded
 * already.
 * 
 */
class InvoiceMetaBoxFactory {
    
    /**
     * Holds all the registered metaboxes
     * @var array
     */
    private static $metaboxes = array();
    
    /**
     * Stores a metabox with the post type as a key
     * and the class name. Used when get is called
     * to instantiate that class.
     * 
     * @access public
     * @param string $post_type
     * @param string $class_name
     */
    public static function register( $post_type, $class_name ) {
        self::$metaboxes[$post_type] = $class_name;
    }
    
    /**
     * Removes a post type metabox from the stored list.
     * 
     * @access public
     * @param string $post_type
     */
    public static function unregister( $post_type ) {
        if ( array_key_exists( $post_type, self::$metaboxes ) )
            unset( self::$metaboxes[$post_type] );
    }
    
    /**
     * Returns a boolean depending on wether the passed
     * post type has a registered class or not.
     * 
     * @access public
     * @param string $post_type
     * @return bool
     */
    public static function supported( $post_type ) {
        return ( array_key_exists( $post_type, self::$metaboxes ) );
    }
    
    /**
     * Returns an instance of the registered class
     * 
     * @access public
     * @param string $post_type
     * @return object
     */
    public static function get( $post_type ) {
        if ( array_key_exists( $post_type, self::$metaboxes ) )
            return new self::$metaboxes[$post_type]();
    }
        
}