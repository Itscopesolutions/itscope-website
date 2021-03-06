<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

    /**
     * Handles integrations with other products and services
     *
     * @since       1.0.8
     */

    class Noptin_Integrations{

    /**
	 * Class Constructor.
	 */
	public function __construct() {

		if( noptin_should_show_optins() ) {

			//Maybe ask users to subscribe to the newsletter after commenting...
			add_filter( 'comment_form_submit_field', array( $this, 'comment_form') );
			add_action( 'comment_post', array( $this, 'subscribe_commentor') );

			//... or when registering
			add_action( 'register_form', array( $this, 'register_form') );
			add_action( 'user_register', array( $this, 'subscribe_registered_user') );

			//Comment prompts
			add_filter( 'comment_post_redirect', array( $this, 'comment_post_redirect' ), 10, 2 );

		}


    }

    /**
     * Maybe ask users to subscribe to the newsletter after commenting
     *
     * @access      public
     * @since       1.0.8
     * @return      void
     */
    public function comment_form( $submit_field ) {

		if(! get_noptin_option( 'comment_form' ) ) {
			return $submit_field;
		}

		$text = get_noptin_option( 'comment_form_msg' );
		if( empty( $text ) ) {
			$text = __( 'Subscribe To Our Newsletter',  'newsletter-optin-box' );
		}

		$checkbox =  "<label class='comment-form-noptin'><input name='noptin-subscribe' type='checkbox' />$text</label>";

		return $checkbox . $submit_field;
	}

	/**
     * Maybe subscribe a commentor
     *
     * @access      public
     * @since       1.0.8
     * @return      void
     */
    public function subscribe_commentor( $comment_id ) {

		if(! get_noptin_option( 'comment_form' ) ) {
			return;
		}

		if( isset( $_POST['noptin-subscribe'] ) ) {
			$author = get_comment_author( $comment_id );

			if( 'Anonymous' == $author ) {
				$author = '';
			}

			$fields = array(
				'email' 		  => get_comment_author_email( $comment_id ),
				'name' 			  => $author,
				'_subscriber_via' => 'comment'
			);

			if(! is_string( add_noptin_subscriber( $fields ) ) ) {
				do_action('noptin_after_add_comment_subscriber');
			}

		}

	}

	/**
     * Maybe ask users to users to register on the registration form
     *
     * @access      public
     * @since       1.0.8
     * @return      void
     */
    public function register_form() {

		if(! get_noptin_option( 'register_form' ) ) {
			return;
		}

		$text = get_noptin_option( 'register_form_msg' );
		if( empty( $text ) ) {
			$text = __( 'Subscribe To Our Newsletter',  'newsletter-optin-box' );
		}

		echo "<label class='register-form-noptin'><input name='noptin-subscribe' type='checkbox' />$text</label>";

	}

	/**
     * Maybe subscribe a registered user
     *
     * @access      public
     * @since       1.0.8
     * @return      void
     */
    public function subscribe_registered_user( $user_id ) {

		if(! get_noptin_option( 'register_form' ) ) {
			return;
		}

		if( isset( $_POST['noptin-subscribe'] ) ) {

			$user = get_userdata( $user_id );

			if(! $user ) {
				return;
			}

			$fields = array(
				'email' 		  => $user->user_email,
				'name' 			  => $user->display_name,
				'_subscriber_via' => 'registration'
			);

			if(! is_string( add_noptin_subscriber( $fields ) ) ) {
				do_action('noptin_after_add_registration_subscriber');
			}

		}

	}

	/**
	 * Redirect to a custom URL after a comment is submitted
	 * Added query arg used for displaying prompt
	 *
	 * @param string $location Redirect URL
	 * @param object $comment Comment object
	 * @return string $location New redirect URL
	 */
	function comment_post_redirect( $location, $comment ) {

		$location = add_query_arg( array(
			'noptin_comment_added' => $comment->comment_ID
		), $location );

		return $location;
	}


}

new Noptin_Integrations();
