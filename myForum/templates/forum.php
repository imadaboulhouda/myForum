<?php 
/**
 * Template Name: Forum List
 */

if(!is_user_logged_in())
{
    wp_redirect('/404');
    die;
}
get_header(); 
?>
<div class='liste'>

<?php 
    $wpQuery = new WP_Query(array(
        'post_type'=>'forumimad',
        'posts_per_page'=>10,
    ));
    while($wpQuery->have_posts()): $wpQuery->the_post();
?>
    <div class='item'>
        <h1><a href="<?php echo get_the_permalink();  ?>"><?php echo get_the_title(); ?></a></h1>
        <p><?php substr(get_the_content(),0,100)."...."; ?></p>

    </div>

<?php
    endwhile;
?>
</div>
<?php 
get_footer(); ?>