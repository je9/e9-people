<?php

function create_person_post_type() {
  $labels = array(
    'name' => 'People',
    'singular_name' => 'Person',
    'add_new' => 'Add New Person',
    'add_new_item' => 'Add New Person',
    'edit_item' => 'Edit Person',
    'new_item' => 'New Person',
    'all_items' => 'All People',
    'view_item' => 'View Person',
    'search_items' => 'Search People',
    'not_found' =>  'No People Found',
    'not_found_in_trash' => 'No People found in Trash',
    'parent_item_colon' => '',
    'menu_name' => 'People',
  );
  register_post_type( 'person', array(
    'labels' => $labels,
    'has_archive' => true,
    'public' => true,
    'supports' => array( 'title', 'thumbnail', 'editor' ),
    'taxonomies' => array( 'post_tag' ),
    'exclude_from_search' => false,
    'capability_type' => 'post',
    'rewrite' => array( 'slug' => 'people' ),
  ));
}
add_action( 'init', 'create_person_post_type' );

function add_details_meta_box() {
  add_meta_box(
    "person-details-meta-box", 
    "Contact details", 
    "person_details_html", 
    "person", 
    "normal", 
    "high", 
    null);
}

add_action("add_meta_boxes", "add_details_meta_box");

function person_details_html($object) {
  wp_nonce_field(basename(__FILE__), "meta-box-nonce");
  
?>

<table class="form-table">
  <tr>
    <th>
      <label for="meta-box-phone">Phone</label>
    </th>
    <td>
      <input 
        name="meta-box-phone" 
        type="text" 
        value="<?php echo get_post_meta($object->ID, "meta-box-phone", true); ?>"
      >
      <p class="help">
        Use the format +6476543210. This will allow mobile users to call the link.
      </p>
    </td>
  </tr>
  <tr>
    <th>
      <label for="meta-box-email">Email</label>
    </th>
    <td>
      <input 
        name="meta-box-email" 
        type="text" 
        value="<?php echo get_post_meta($object->ID, "meta-box-email", true); ?>"
      >
    </td>
  </tr>
</table>

<?php

}

function save_person_meta_box($post_id, $post, $update) {
  
  if (
    !isset($_POST["meta-box-nonce"]) 
    || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))
  ) :
    return $post_id;
  endif;

  if (!current_user_can("edit_post", $post_id)) :
    return $post_id;
  endif;

  if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) :
    return $post_id;
  endif;

  $slug = "person";
  if ($slug != $post->post_type) :
    return $post_id;
  endif;

  $meta_box_phone_value = "";
  $meta_box_email_value = "";

  if (isset($_POST["meta-box-phone"])) :
    $meta_box_phone_value = $_POST["meta-box-phone"];
  endif;
  update_post_meta($post_id, "meta-box-phone", $meta_box_phone_value);
  
  if (isset($_POST["meta-box-email"])) :
    $meta_box_email_value = $_POST["meta-box-email"];
  endif;
  update_post_meta($post_id, "meta-box-email", $meta_box_email_value);
    
}

add_action("save_post", "save_person_meta_box", 10, 3);
