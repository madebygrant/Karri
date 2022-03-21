<?php
/*
    Page: Comments
*/

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

// ----------------------------

if ( post_password_required() ) {
	return;
}

$comments_number = absint( get_comments_number() );

if ( $comments_number > 0 ) {
	?>

	<div class="comments">
        <div class="inner inner--container inner--comments">

            <div class="comments-header">

                <h2 class="heading heading--h3 comments-header__heading">
                <?php
                    if ( !have_comments() ) {
                        _e( 'Leave a comment', 'karri' );
                        
                    } else if ( $comments_number === '1' ) {
                        _e( 'One reply on', 'karri' ). ' ' . esc_html( get_the_title() );
                    } 
                    else {
                        echo '<span class="comments-header__number">'. esc_html( $comments_number ) . '</span> ' . __( 'replies for', 'karri' ) . ' <span class="comments-header__title">' . esc_html( get_the_title() ) . '</span>';
                    }
                    ?>
                </h2>

            </div>

            <ul class="comments-list">

                <?php
                
                // Comments: List
                wp_list_comments();

                // Comments: Pagination
                $comment_pagination = paginate_comments_links( [
                    'echo'      => false,
                    'end_size'  => 0,
                    'mid_size'  => 0,
                    'next_text' => __( 'Newer Comments', 'karri' ) . ' <span aria-hidden="true">&rarr;</span>',
                    'prev_text' => '<span aria-hidden="true">&larr;</span> ' . __( 'Older Comments', 'karri' ),
                ] );

                if ( $comment_pagination ):
                    ?>
                    <nav class="pagination pagination--comments">
                        <?php echo wp_kses_post( $comment_pagination ); ?>
                    </nav>
                    <?php
                endif;
                ?>

            </ul>
        
        </div>
	</div>

	<?php

    if ( comments_open() || pings_open() ) {

        if ( $comments ) {
            echo '<hr class="container comments-separator" />';
        }

        ?>

        <div class="container comments-form">
            <?php
            comment_form( [
                'class_form' => 'comment-respond__form',
                'id_form' => 'comment-respond__form',
                'title_reply_before' => '<h2 class="heading heading--h4 comment-respond__heading">',
                'title_reply_after'  => '</h2>'
            ] );
            ?>
        </div>

        <?php

    }

    else if ( is_single() ) {

        if ( $comments ) {
            echo '<hr class="container comments-separator" />';
        }

    ?>

        <div class="comments-respond comments-respond--closed">
            <p>
                <?php _e( 'Comments are closed.', 'karri' ); ?>
            </p>
        </div>

    <?php
    }

}