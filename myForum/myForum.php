<?php 
/** 
 * Plugin Name:My Forum IMAD ABOULHOUDA
 * Description: Create forum and Roles 
 * Version:1.0
 * Author: IMAD ABOULHOUDA
 * Author URI: https://facebook.com/aboulhoudaimad7
 */


add_theme_support('post-thumbnails');
 add_role( 'custom_role_ok', 'userSimple', array( 'read' => true, 'level_0' => true ) );

 add_action('init','imad_createPostType');
function your_function( $user_login, $user ) {
  
    if(in_array( 'custom_role_ok', (array) $user->roles ))
    {
        $p = new WP_Query(array(
            'post_type'=>'page',
            'posts_per_page'=>1,
            'meta_query' => array(
                array(
                    'key' => 'typePage',
                    'value' => 'forum',
                    'compare' => '=',
                )
                ),
            ));
            $p->the_post();
            $link = get_the_permalink();
            
        wp_redirect($link);
        die;
    }
}
add_action('wp_login', 'your_function', 10, 2);
 function imad_createPostType()
 {

    

     register_post_type('forumimad',[
        'public'=>true,
        'labels'=>[
            'name'=>'Forum',
        'singular_name'=>'Forum',
            'add_new_item'=>'Ajouter une disscussion'
        ],
        'supports'=>['title','editor','thumbnail','comments']
        
     ]);
 }

add_action('save_post_forumimad','savePostForum');
 function savePostForum()
 {
     update_post_meta(get_the_ID(),'user_id',get_current_user_id());
 }


 add_action('manage_forumimad_posts_columns','columnsForum');
 function columnsForum($col)
 {
    
     $col['content'] = "Contenu";
      $col['user'] = "Créer par";
     return $col; 
 }
 add_action('manage_forumimad_posts_custom_column','columnShow',10,2);
 function columnShow($col,$postID)
 {
     
    switch($col)
    {
        case 'user':
        $id=  get_post_meta($postID,'user_id',true);
        $user = get_user_by('id',$id);
        echo (isset($user->user_nicename)) ?  $user->user_nicename : "";
        break;
        case 'content':
            the_content();
        break;
    }
 }

add_filter( "single_template", "plugin_function_name" );
function plugin_function_name($page)
{
    if(get_post_type() == "forumimad")
    {
        $page = dirname(__FILE__)."/templates/detail.php";
    }
 
    return $page;
}

add_filter("page_template",'Page');
function Page($page)
{
    if( get_post_meta(get_the_ID(),'typePage',true) == "forum")
    {
        $page = dirname(__FILE__)."/templates/forum.php";
    }
    return $page;
}

add_filter('use_block_editor_for_post', '__return_false', 10);

add_action('add_meta_boxes','initialisation_metaboxes');
function initialisation_metaboxes()
{
add_meta_box('tag', 'Type Page', 'showList', 'page');
}

function showList()
{
    $type = get_post_meta(get_the_ID(),'typePage',true);
    ?>
    <select name="typePage">
    <option value="">----</option>
    <option <?php echo ($type == "forum") ? "selected" : ""; ?> value="forum">Forum</option>
    </select>
    <?php 
}

add_action('save_post_page','savePost');

function savePost()
{
    if(isset($_POST['typePage']))
    {
        update_post_meta(get_the_ID(),'typePage',$_POST['typePage']);
    }
}