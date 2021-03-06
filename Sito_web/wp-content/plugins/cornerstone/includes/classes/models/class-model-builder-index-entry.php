<?php

class Cornerstone_Model_Builder_Index_Entry extends Cornerstone_Plugin_Component {

  public $resources = array();
  public $name = 'builder/index/entry';

  public function setup() {

    $posts = get_posts( array(
      'post_type' => $this->plugin->common()->getAllowedPostTypes(),
      'orderby' => 'type',
      'posts_per_page' => 2500
    ) );

    $records = array();

    foreach ($posts as $post) {

      $post_type_obj = get_post_type_object( $post->post_type );

      $records[] = array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'post-type' => $post->post_type,
        'post-type-label' => $post_type_obj->labels->singular_name,
        'modified' => date_i18n( get_option( 'date_format' ), strtotime( $post->post_modified ) ),
        'permalink' => get_permalink( $post )
      );

    }

    foreach ($records as $record) {
      $this->resources[] = $this->to_resource( $record );
    }
  }

  public function query( $params ) {

    // Find All
    if ( empty( $params ) || ! isset( $params['query'] ) ) {
      return array(
        'data' => $this->resources
      );
    }

    $queried = array();
    $included = array();

    foreach ( $this->resources as $resource) {
      if ( $this->query_match( $resource, $params['query'] ) ) {
        $queried[] = $resource;
      } else {
        $included[] = $resource;
      }
    }

    return array(
      'data' => ( isset( $params['single'] ) ) ? $queried[0] : $queried,
      'included' => $included,
      'meta' => array( 'request_params' => $params )
    );
  }

  public function query_match( $resource, $query ) {

    foreach ( $query as $key => $value ) {

      // Check relationships
      if ( isset( $resource['relationships'][ $key ] )  ) {

        if ( ! isset( $resource['relationships'][ $key ]['data'] ) ) {
          return false;
        }

        $data = $resource['relationships'][ $key ]['data'];

        if ( isset( $data['id'] ) && $value !== $data['id'] ) {
          return false;
        } else {
          foreach ( $data as $child ) {
            if ( isset( $data['id'] ) && $value === $data['id'] ) {
              return true;
            }
          }
          return false;
        }

      } else {
        if ( ! isset( $resource[ $key ] ) || $resource[ $key ] !== $value ) {
          return false;
        }
      }

    }

    return true;
  }

  public function to_resource( $record ) {

    $resource = array(
      'id' => $record['id'],
      'type' => $this->name
    );

    unset( $record['id'] );
    $resource['attributes'] = $record;

    return $resource;

  }
}
