<?php

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * NavWalker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 *
 * You can enable/disable this feature in functions.php (or lib/setup.php if you're using Sage):
 * add_theme_support('soil-nav-walker');
 */
class Avante_NavWalker_Mobile extends Walker_Nav_Menu {
  private $cpt; // Boolean, is current post a custom post type
  private $archive; // Stores the archive page for current URL

  public function __construct() {
    add_filter('nav_menu_css_class', array($this, 'cssClasses'), 10, 3);
    //add_filter('nav_menu_item_id', '__return_null');
    $cpt           = get_post_type();
    $this->cpt     = in_array($cpt, get_post_types(array('_builtin' => false)));
    $this->archive = get_post_type_archive_link($cpt);
  }

  public function checkCurrent($classes) {
    return preg_match('/(current[-_])|active/', $classes);
  }

  public function walk( $elements, $max_depth, ...$args ) {

      // we could declare above, but would get incompatible signature
      $args = func_get_arg(2);

      $this->args = $args;

      return parent::walk( $elements, $max_depth, $args );
  }

  // @codingStandardsIgnoreStart
  function start_lvl(&$output, $depth = 0, $args = array()) {
    $output .= "\n<div class=\"dropdown-menu-wrapper\"><ul class=\"mobile-dropdown-menu\">\n";
  }

  function end_lvl(&$output, $depth = 0, $args = array()) {
    $output .= "</ul></div>\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    global $redux_proveg;

    $item_html = '';
    parent::start_el($item_html, $item, $depth, $args);

    $item_html = str_replace('<a', '<a class="nav-link"', $item_html);

    if ($item->is_subitem && ($depth === 0)) {
      $item_html = str_replace('<a', '<a class="nav-link dropdown-toggle" data-target="#"', $item_html);
      $item_html = str_replace('</a>', '</a><b class="caret"></b>', $item_html);
    }

    $output .= $item_html;

  }

  public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
    $element->is_subitem = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

    if ($element->is_subitem) {

      foreach ($children_elements[$element->ID] as $child) {
        if ($child->current_item_parent || avante_url_compare($this->archive, $child->url)) {
          $element->classes[] = 'active';
        }
      }
    }

    $element->is_active = (!empty($element->url) && strpos($this->archive, $element->url));

    if ($element->is_active) {
      $element->classes[] = 'active';
    }


    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
  // @codingStandardsIgnoreEnd


  public function cssClasses($classes, $item, $args) {
    $menu_args = $this->args;

    if( $args->theme_location === $menu_args->theme_location ) {
      $slug = sanitize_title($item->title);

      // Fix core `active` behavior for custom post types
      if ($this->cpt) {
        $classes = str_replace('current_page_parent', '', $classes);

        if (avante_url_compare($this->archive, $item->url)) {
          $classes[] = 'active';
        }
      }

      // Remove most core classes
      $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
      $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

      // Re-add core `nav-item` class
      if ($item->menu_item_parent) {
          $classes[] = 'mobile-dropdown-item';
      } else {
          $classes[] = 'nav-item';
      }

      // Re-add core `nav-item-has-children` class on parent elements
      if ($item->is_subitem) {
        $classes[] = 'nav-item-has-children';
      }

      // Add `menu-<slug>` class
      $classes[] = 'nav-' . $slug;

      $classes = array_unique($classes);
      $classes = array_map('trim', $classes);
    }

    return array_filter($classes);
  }
}


/**
 * Helper function
 * Make a URL relative
 */
if ( ! function_exists( 'avante_root_relative_url' ) ) {

  function avante_root_relative_url($input) {
    $url = parse_url($input);
    if (!isset($url['host']) || !isset($url['path'])) {
      return $input;
    }
    $site_url = parse_url(network_site_url());  // falls back to site_url

    if (!isset($url['scheme'])) {
      $url['scheme'] = $site_url['scheme'];
    }
    $hosts_match = $site_url['host'] === $url['host'];
    $schemes_match = $site_url['scheme'] === $url['scheme'];
    $ports_exist = isset($site_url['port']) && isset($url['port']);
    $ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;

    if ($hosts_match && $schemes_match && $ports_match) {
      return wp_make_link_relative($input);
    }
    return $input;
  }

}

/**
 * Helper function
 * Compare URL against relative URL
 */

if ( ! function_exists( 'avante_url_compare' ) ) {

  function avante_url_compare($url, $rel) {
    $url = trailingslashit($url);
    $rel = trailingslashit($rel);
    return ((strcasecmp($url, $rel) === 0) || avante_root_relative_url($url) == $rel);
  }

}