# Codeless Library
An helper library with functionalities that are common among my WP projects.

## Features

- 2 functions to check if WP_DEBUG and SCRIPT_DEBUG are defined.
- Utility class to display admin notices with ability to dismiss notices via ajax.
- Utility function to add plugin action links.
- Utility function to add plugin row meta links.
- Utility function to add a plugin message ( similar to the update message into the plugin's page ).
- Count bubble.
- Helper function to add admin menu bar items.
- Add columns to the user's table.
- Add columns to post types tables.
- Add columns to taxonomies tables.
- Add new action links to post rows.
- A jQuery modal interface that replicates the look and feel of WP's media manager modals.

## Initialize the class

Include the `wp-codeless-lib.php` file into your project and then:

```php
$helper = new TDP\Codeless;
```

### Check if WP_DEBUG is defined

```php
if( $helper::is_development() )
```

### Check if WP_DEBUG and SCRIPT_DEBUG are defined

```php
if( $helper::is_script_debug() )
```

### Show an admin notice

```php
$helper::show_admin_notice( $content = 'The message' , $type = 'success' , $id );
```

If `$id` is defined, the message will be set as "sticky". Sticky notices will stay visible until the user dismisses the message.

Dismissed messages are stored into the `wp_codeless_dismissed_notices` option.

### Add plugin action link

```php
$helper::add_plugin_action_link( $plugin_slug = 'plugin-folder/plugin-file.php', $label = 'Custom link', $link = '#' );
```

### Add plugin row meta link

```php
$helper::add_plugin_meta_link( $plugin_slug = 'plugin-folder/plugin-file.php', $label = 'Custom link', $link = '#' );
```

### Add plugin message

This message appears below the plugin's row within the plugins page. Usually this area is also used for the update notice displayed by WP.

```php
$helper::add_plugin_message( $plugin_slug = 'plugin-folder/plugin-file.php', $message = 'Message', $type = 'update-message' );
```

`$type` defines the class added to the message container. When using `update-message` the message will use same layout as the update notice.

### Add count bubble

```php
$helper::add_count_bubble( $key = '10', $counter = '11' );
```

Access the global variable `$menu` to find out your menu keys.

### Add items to the admin menu bar

```php
$helper::add_menu_bar_item( $args );
```

Refer to [https://codex.wordpress.org/Class_Reference/WP_Admin_Bar/add_menu](https://codex.wordpress.org/Class_Reference/WP_Admin_Bar/add_menu) for the $args.

### Add columns to the user's table

```php
$helper::add_user_column( $label = 'Custom column', $callback = 'my_callback_function', $priority = 10 );

function my_callback_function( $user_id ) {

  echo $user_id;

}
```

### Add post type column

```php
$helper::add_post_type_column( $post_types = 'post', $label = 'Custom column', $callback = 'my_callback_function', $priority = 10 );

function my_callback_function( $post_id ) {

  echo $post_id;

}
```

`$post_types` can also be an array of post types.

### Add taxonomy column

```php
$helper::add_taxonomy_column( $taxonomies = 'post_tag', $label = 'Custom column', $callback = 'my_callback_function', $priority = 10 );

function my_callback_function( $tax_id ) {

  echo $tax_id;

}
```

`$taxonomies` can also be an array of taxonomies.

### Add post row action links

```php
php
$helper::add_post_row_action( $post_type = 'post', $label = 'Custom link', $link = '#' );
```

### Using the built-in modal interface

#### Load the required scripts:

```php
function codeless_modal_scripts() {

  $helper = new TDP\Codeless;

  $helper::add_ui_helper_files();

}
add_action( 'admin_enqueue_scripts', 'codeless_modal_scripts' );
```

#### Create the modal within an admin page:

##### The jQuery part:

```html
<script>

  jQuery(document).ready(function ($) {

    jQuery( '.trigger' ).click(function() {

      var popup = null;

      popup = codelessUi.popup()
        .modal( true )
        .size( 740, 480 )
        .title( 'Window title' )
        .content( '.popupcontainer' )
        .show();

    });

  });

</script>
```

##### The html part:

```html
<a href="#" class="trigger">Trigger</a>

<div class="popupcontainer" style="display:none">
  Yo!
</div>
```

Ps: don't use inline css, this was just an example.

### Plugin template loader

Template files are standard for themes. For example if a user wishes to customize a template file he/she can just copy and file into the child theme and modify it. Unfortunately this isn't possible with plugins unless developers build their own custom template loader.

Many plugins like WooCommerce and EDD have their own template builder so that developers can customize the look of plugin.

The Codeless library comes with it's own template loader that can be reused as many times as you want. It was highly inspired by the WP Job Manager plugin, and WooCommerce.

The template loader into the Codeless library is also capable of recognizing which template files are being overwritten by a theme and if the template file into the theme is outdated.

The following is the order with which template files are loaded:

1. Child Theme
2. Parent Theme
3. Plugin’s folder

Here's an example of how to user the template loader.

##### Extend the template loader class and configure it for your plugin:

```php
define( 'RESTAURANT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

class Restaurant_Template_Loader extends \TDP\Plugin_Template_Loader {

  // Prefix for the filters within the class.
  protected $filter_prefix = 'restaurant_plugin';

  // Path to the plugin.
  protected $plugin_directory = RESTAURANT_PLUGIN_DIR;

  // Name of the folder that contains all the templates within the plugin.
  protected $plugin_template_directory = 'templates';

  // Name of the folder that contains the templates within a theme.
  protected $theme_template_directory = 'restaurant-templates';

}

$restaurant_template_loader = new Restaurant_Template_Loader;
```

##### Load the template file:

To load a template file you can now access the get_template method:

```php
$restaurant_template_loader->get_template( 'file.php', array( 'my_variable' => 'value' ) );
```

You can pass arguments via an array to template files and then access the data in it `echo $my_variable`.

##### Checking overwritten files:

If you wish to check which files have been overwritten by a theme, you can access the `get_overwritten_template_files` method:

```php
$restaurant_template_loader->get_overwritten_template_files();
```

The method will return an array with details about the files overwritten and whether or not the file is outdated.

If you wish to use this feature, your template files must have the following comments at the top of the file. You must define @version.

```php
/**
 * Template file comments here...
 *
 * @version 1.0.0
 */
```
