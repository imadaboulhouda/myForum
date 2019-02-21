<?php 

if(!is_user_logged_in())
{
    wp_redirect('/404');
    die;
}

 get_header();
 the_post();
 ?>
 
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <?php 
    $comments = get_comments([
        'post_id'=>get_the_ID(),
    ]);
    foreach( $comments as $comment):
        ?>
    <div class="comments">
    
        <p><?php echo $comment->comment_content ?></p>
        <p><?php echo $comment->comment_author; ?></p>
    </div>
        <?php
    endforeach;
    comment_form();
    ?>
 <?php 
 get_footer();
 ?>