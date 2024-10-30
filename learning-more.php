<?php
/*
Plugin Name: Learning More
Plugin URI: http://www.keighl.com/plugins/learning-more/
Description: Adds a simple contact widget to the sidebar. Let's a user quickly send you an email requesting more info. AJAX validation.
Version: 0.2
Author: Kyle Truscott
Author URI: http://www.keighl.com
*/

/*

Style 

.learning_more_success {
	display:none;
}

.learning_more_invalid {
	background-color:#ffcaca;
}

.learning_more_valid {
	background-color:#d3ffca;
}

#learning_more_name. #learning_more_email {

}

#learning_more_submit {

}

*/

add_action("widgets_init", 'learning_more_init');
add_action('init', "learning_more_js_libs");
add_action('wp_head', "learning_more_js");
add_action('wp_head', "learning_more_css_libs");
add_action('wp_ajax_learning_more_mail', "learning_more_mail");

function learning_more_init() {
	
	register_widget('LearningMore');
	
}

function learning_more_js_libs() {
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	
}

function learning_more_css_libs() {
	?>
	
	<style type="text/css">
		
		.learning_more_success {
			display:none;
		}

		.learning_more_invalid {
			background-color:#ffcaca;
		}

		.learning_more_valid {
			background-color:#d3ffca;
		}

	</style>
	
	<?php
}

function learning_more_js() {
	
	?>
		
	<script type="text/javascript">
		
		jQuery(document).ready(function($) {
			
			$('#learning_more_name, #learning_more_email').click(
				function() {
					var val = $(this).val();
					if (val == "Name" || val == "Email" ) {
						$(this).val("");
					}
				}
			);
			
			$("input#learning_more_submit").click(
				function() {
									
					$('input').removeClass('learning_more_invalid');
					$('input').removeClass('learning_more_valid');
					
					var sendEmail = $("#learning_more_send_email").val();
					var title = $("#learning_more_title").val();
					var name = $('#learning_more_name').val();
					var email = $('#learning_more_email').val();
					var emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*\.(\w{2}|(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum))$/;
					
					if (!name) {
						$('#learning_more_name').addClass('learning_more_invalid');
					} else if (!emailRegex.test(email)) {
						$('#learning_more_email').addClass('learning_more_invalid');
					} else {
						$.post(
							"<?php echo get_option('siteurl'); ?>/wp-admin/admin-ajax.php",
							{
								action : "learning_more_mail",
								name : name,
								email : email,
								sendEmail : sendEmail,
								title : title
							} ,
							function(str) {
								$('#learning_more_name').addClass('learning_more_valid');
								$('#learning_more_email').addClass('learning_more_valid');
								$('.learning_more_success').fadeIn("slow");
							}
						);
					}
					
				}
			);
			
			
		});
		
	</script>
	
	<?php
}

function learning_more_mail() {
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$sendEmail = $_POST['sendEmail'];
	$title = $_POST['title'];
	
	$to = $sendEmail;
	$headers = "From: $email";
	$subject = $title;

	$message = "
		Name: $name \n\n
		Email: $email \n\n
	";

	$mail = mail($to, $subject, $message, $headers);
	
	exit();
	
}

class LearningMore extends WP_Widget {
	
	function LearningMore() {		
				
		$widget_ops = array( 'classname' => 'learning_more', 'description' => 'Adds a simple "Learn More" box' );

		$control_ops = array( 'id_base' => 'learning_more' );

		$this->WP_Widget( 'learning_more', __('Learning More', 'learning_more'), $widget_ops, $control_ops );
		
	}
	
	function form($instance) {
		
		global $user_ID;
		
		$user_email = get_usermeta($user_ID, 'user_email');
		
		$defaults = array( 
			'title' => _('Learn More'),
			'success' => _("Thank you for your interest! We will contact you shortly."),
			'email' => $user_email
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );		
		
		?>
		
		
		<table width="100%" cellspacing="6">
			<!-- Title -->
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e('Title:'); ?></strong></label>
				</td>
				<td>
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
				</td>
			</tr>
			<!-- Email -->
			<tr>
				<td>
					<label for="<?php echo $this->get_field_id( 'email' ); ?>"><strong><?php _e('Email:', 'learning_more'); ?></strong>
				</td>
				<td>
					<input class="widefat" id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" />
				</td>
			</tr>
			<!-- Success -->
			<tr>
				<td valign="top">
					<label for="<?php echo $this->get_field_id( 'success' ); ?>"><strong><?php _e('Success:', 'learning_more'); ?></strong>
				</td>
				<td>
					<textarea class="widefat" id="<?php echo $this->get_field_id( 'success' ); ?>" name="<?php echo $this->get_field_name( 'success' ); ?>"><?php echo $instance['success']; ?></textarea>
				</td>
			</tr>
		</table>		
		
		<?php
		
	}
	
	function update($new_instance, $old_instance) {
			
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['email'] = strip_tags( $new_instance['email'] );
		$instance['success'] = strip_tags( $new_instance['success'] );

		return $instance;	
		
	}
		
	function widget($args, $instance) {
		
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title'] );
		$email = $instance['email'];
		$success = $instance['success'];
		
		echo $before_widget;

			if ($title)
				echo $before_title . $title . $after_title;

			if ($email) :
				
				echo '<div class="learning_more">';
				
				echo '<input type="hidden" id="learning_more_send_email" value="' . $email . '" />';
				echo '<input type="hidden" id="learning_more_title" value="' . $instance['title'] . '" />';
			
				echo '<p><input type="text" id="learning_more_name" value="Name" /></p>';
				
				echo '<p><input type="text" id="learning_more_email" value="Email" /></p>';
				
				echo '<p><input type="button" id="learning_more_submit" value="Submit" /></p>';
				
				echo '<p class="learning_more_success">' . $success . '</p>';
				
				echo '</div>';
			
			endif;

		echo $after_widget;
			
	}
	
}

?>