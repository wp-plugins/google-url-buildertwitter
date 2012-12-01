<?php
/*
Plugin Name: Google URL Builder::Twitter
Plugin URI: http://www.danielrosca.ro/blog/en/wordpress-plugin-google-url-buildertwitter/
Description: Adds a Twitter button at the beginning of each post. The link inside the tweet is built by Google URL Builder Campaigns.
Author: Daniel Rosca
Author URI: http://www.danielrosca.ro
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/* Define customizable variables */
define('TWITTER_ACCOUNT', 'your_twitter_username'); //replace your_twitter_username with your own Twitter account username. It will appear as via @your_twitter_username
define('CAMPAIGN_SOURCE', 'Twitter%2BName'); //replace ' ' with '%2B' - example: 'Twitter Name' => 'Twitter%2BName' 
define('CAMPAIGN_MEDIUM', 'twitter-blog'); //replace twitter-blog with your own value
define('TWEET_MESSAGE', 'your_custom_text_right_here'); //this string applies in the tweet message after the article title. You can leave it empty
//bitly info
define('BITLY_LOGIN', 'login_key'); //your bitly login key
define('BITLY_API', 'api_key'); //your bitly API Key

/* Fire meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'dr_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'dr_post_meta_boxes_setup' );

/* Meta box setup function. */
function dr_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'dr_add_post_meta_boxes' );
	
	/* Save post meta on the 'save_post' hook. */
    add_action( 'save_post', 'dr_save_url_twitter_meta', 10, 2 );
	
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function dr_add_post_meta_boxes() {

	add_meta_box(
		'dr-url-twitter',									
		esc_html__( 'Build URL Twitter', 'example' ),		
		'dr_url_twitter_meta_box',							
		'post',												
		'side',												
		'default'											
	);
}

/* Display the post meta box. */
function dr_url_twitter_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'dr_url_twitter_nonce' ); ?>

	<p>
		<label for="dr-url-twitter"><?php _e( "Enter your post title so you can keep track of this article into Google Analytics", 'example' ); ?></label>
		<br />
		<input class="widefat" type="text" name="dr-url-twitter" id="dr-url-twitter" value="<?php echo the_title(); ?>" size="30" />
	</p>
<?php }

/* Save the meta box's post metadata. */
function dr_save_url_twitter_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['dr_url_twitter_nonce'] ) || !wp_verify_nonce( $_POST['dr_url_twitter_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Define variables via POST */

	/* Build the URL */
	$value = str_replace(' ', '%2B', $_POST['dr-url-twitter']);
	$new_meta_value = '?utm_source=' . CAMPAIGN_SOURCE . '&utm_medium=' . CAMPAIGN_MEDIUM . '&utm_campaign=' . $value;

	/* Get the meta key. */
	$meta_key = 'dr_url_twitter';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}

/* Filter the content hook with twitter url function. */
add_filter( 'the_content', 'dr_url_twitter' );

function dr_url_twitter( $content ) {

	/* Get the current post ID. */
	$post_id = get_the_ID();

	/* If we have a post ID, proceed. */
	if ( !empty( $post_id ) ) {

		/* Get the URL */
		$url_twitter = get_post_meta( $post_id, 'dr_url_twitter', true );

		/* If isset, place it at the beginning of the content */
		if ( !empty( $url_twitter ) )		
			$perma		= get_permalink();
			$link_string= $perma . '' . $url_twitter;
			$title		= the_title('', '', false);
			
			if(isset($perma)) {
				$content	= '<a href="https://twitter.com/share" class="twitter-share-button" style="float: right;" data-text="' . $title . TWEET_MESSAGE . '" data-url="' . bitly($link_string) . '" data-via="' . TWITTER_ACCOUNT . '" data-size="large" data-count="none"> </a>' . $content;	
			}
	}

	return $content;
}

/* Add support for bit.ly */
function bitly($url) {
	/**
	 * You should have a bitly.com account
	 * Replace YOUR_LOGIN with the login provided by bitly
	 * Replace YOUR_API without your API key from bitly
	 */
	$url 		= rawurlencode($url); //encode URL for bitly v3 API
	$content 	= file_get_contents("http://api.bit.ly/v3/shorten?login=" . BITLY_LOGIN . "&apiKey=" . BITLY_API . "&longUrl=".$url."&format=xml");
	$element 	= new SimpleXmlElement($content);
	$bitly 		= $element->data->url;
	if($bitly){
		return $bitly;}
	else{
		return FALSE;
	}
}
