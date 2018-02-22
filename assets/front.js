/**
 * Plugin front end scripts
 *
 * @package Lesson_Template_LLMS
 * @version 1.0.0
 */
jQuery(function ($) {
	var
		$itb = $( '.index-tabs' ),
		$itbc = $itb.siblings( '.index-tabs-content' ),
		$tbTitle = $itbc.find( '#index-tab-title' );
	$itb.find( 'a.button' ).click( function() {
		var $t = $( this );
		$itb.find( '.active' ).removeClass( 'active' );
		$t.addClass( 'active' );
		$itbc.find( '.active-tab' ).removeClass( 'active-tab' );
		$itbc.find( $t.attr( 'href' ) ).addClass( 'active-tab' );
		$tbTitle.text( $t.data( 'title' ) );
	} );
});