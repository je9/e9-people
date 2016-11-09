<?php

function format_phone($number) {
  $formatted = preg_replace('/[.\/\- (),]/', '', $number);
  $formatted = substr_replace( $formatted, '-', 7, 0);
  $formatted = substr_replace( $formatted, ') ', 4, 0);
  $formatted = preg_replace('/\+\d\d/', '(0', $formatted);
  return $formatted;
}

function e9_excerpt($post, $max = 132) {
  $excerpt = preg_replace('/<img[^>]+>/' ,'' ,$post->post_content);
  if (strlen($excerpt) > $max) :
    $excerpt = substr($excerpt, 0, $max);
    $contarr = explode(' ', $excerpt);
    array_pop( $contarr );
    $excerpt = implode(' ', $contarr);
    if (is_home() ||
      is_archive() ||
      is_single() ||
      is_search() ||
      is_404()) :
      $excerpt .= '&hellip; ';
      $excerpt .= '<a href="' . get_post_permalink($post->ID, true) . '">[Read more]</a>';
    endif;
  endif;
  return $excerpt;
}

function e9_people_template() {

  $return = '<div class="e9-people">';

  $args = [
    'post_type' => 'person',
    'posts_per_page' => 6
  ];
  $query =  new WP_Query($args);
  $person_number = 0;
  if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
      $excerpt = e9_excerpt($query->post);
      $contact = get_post_meta($query->post->ID, '', true)[0];
      $email = $contact['email'];
      $phone = $contact['phone'];
      $thumb_id = get_post_thumbnail_id($query->post->ID);
      $thumb = wp_get_attachment_image_src($thumb_id,'medium');
      $img_class = $thumb[1] > $thumb[2] ? 'landscape' : 'portrait';
      $return .= '<div class="one-fourth' . ($person_number % 4 == 0 ? ' first' : '') . '" data-order="' . $person_number . '">';
      $return .= '<div class="e9-person">';
      $return .= '<div class="ratio-square"><img src="' . $thumb[0] . '" alt="' . $query->post->post_title . '" class="' . $img_class . '" /></div>';
      $return .= '<h3>' . $query->post->post_title . '</h3>';
      $return .= '<p>' . $excerpt;
      if (!empty( $excerpt)): 
        $return .= '&hellip;';
      endif;
      $return .= '<span class="more-link" data-copy="' . $query->post->post_content . '" data-order="' . $person_number++ . '">';
      if (!empty( $excerpt)): 
        $return .= ' (Read More) ';
      endif;
      $return .= '</span></p>';
      if (!empty($phone)):
        $return .= '<p class="person-contact"><span class="contact-phone">Phone </span>';
        $return .= '<a href="tel:' . $phone .'">' . format_phone( $phone ) . '</a></p>';
      endif;
      if (!empty($email)):
        $return .= '<p class="person-contact"><span class="red">Email </span>';
        $return .= '<a href="mailto:' . $email . '">' . $email . '</a></p>';
      endif;
      $return .= '</div></div>';
    endwhile;
    $return .= '</div>';
  else :
    $return = '<p>There is nobody to show. Add People for content.</p>';
  endif;
  return $return;
}
