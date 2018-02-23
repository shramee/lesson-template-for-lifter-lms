<?php
/*
Plugin Name: Lesson template for lifter LMS
Plugin URI: http://shramee.me/
Description: Simple plugin starter for quick delivery
Author: Shramee
Version: 1.0.0
Author URI: http://shramee.me/
@developer shramee <shramee.srivastav@gmail.com>
*/

/** Plugin public class */
require 'inc/class-public.php';

/**
 * Lesson template for lifter LMS main class
 * @static string $token Plugin token
 * @static string $file Plugin __FILE__
 * @static string $url Plugin root dir url
 * @static string $path Plugin root dir path
 * @static string $version Plugin version
 */
class Lesson_Template_LLMS {

	/** @var Lesson_Template_LLMS Instance */
	private static $_instance = null;

	/** @var string Token */
	public static $token;

	/** @var string Version */
	public static $version;

	/** @var string Plugin main __FILE__ */
	public static $file;

	/** @var string Plugin directory url */
	public static $url;

	/** @var string Plugin directory path */
	public static $path;

	/** @var Lesson_Template_LLMS_Public Instance */
	public $public;

	/**
	 * Return class instance
	 * @return Lesson_Template_LLMS instance
	 */
	public static function instance( $file ) {
		if ( null == self::$_instance ) {
			self::$_instance = new self( $file );
		}
		return self::$_instance;
	}

	/**
	 * Constructor function.
	 * @param string $file __FILE__ of the main plugin
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct( $file ) {

		self::$token   = 'lesson-template-for-lifter-lms';
		self::$file    = $file;
		self::$url     = plugin_dir_url( $file );
		self::$path    = plugin_dir_path( $file );
		self::$version = '1.0.0';

		$this->_init(); //Initiate public

	}

	/**
	 * Initiates public class and adds public hooks
	 */
	private function _init() {
		//Instantiating public class
		$this->public = Lesson_Template_LLMS_Public::instance();

		//Enqueue front end JS and CSS
		add_action( 'wp_enqueue_scripts',	array( $this->public, 'init' ), 99 );
		add_filter( 'llms_widget_syllabus_section_title', [ $this->public, 'syllabus_section_link' ], 10, 2 );
	}
}

/** Intantiating main plugin class */
Lesson_Template_LLMS::instance( __FILE__ );
