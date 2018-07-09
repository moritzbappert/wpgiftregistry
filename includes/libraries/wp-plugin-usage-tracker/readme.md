## WP Plugin Usage Tracker

An helper class that allows developers to track usage of their plugins. The following class is integrated with [Keen.io](https://keen.io/).

### Requirements:

- [Keen.io](https://keen.io/) Account.
- [Keen.io](https://keen.io/) Project ID.
- [Keen.io](https://keen.io/) Write key.
- Composer
- WP Cron enabled on the server.

### How it works:

The class will display an admin notice asking user's permission to track data from his site. The notice will appear on X days since plugin's installation date. The installation date must be somehow retrieved from your own plugin (example: on plugin's activation hook). If the user approves, collected data will be sent to your own Keen.io account once a month via WP Cron.

You can also extend the class and customize the `schedule_tracking` method if you wish to change the cron frequency.

It's recommended that you extend the class so you can customize the notice and the data you wish to collect.

The class has the following 6 parameters all described [here.](https://github.com/alessandrotesoro/wp-plugin-usage-tracker/blob/master/wp-plugin-usage-tracker.php#L83)

`__construct( $plugin_prefix, $plugin_name, $installation_date, $days_passed, $project_id, $write_key )`

#### Extend the class

**Note: data sent to Keen.io must be an array.**

```php
class My_Tracker extends WP_Plugin_Usage_Tracker {

  public function __construct( $plugin_prefix, $plugin_name, $installation_date, $days_passed, $project_id, $write_key ) {

    parent::__construct( $plugin_prefix, $plugin_name, $installation_date, $days_passed, $project_id, $write_key );

  }

  public function get_message() {

		$message = esc_html__( 'My own message ;) ' );
		$message .= ' <a href="'. esc_url( $this->get_tracking_approval_url() ) .'" class="button-primary">'. esc_html( 'Allow tracking' ) .'</a>';

		return $message;

	}

  public function get_data() {

		$data = array();

		$data['php_version']    = phpversion();
		$data['wp_version']     = get_bloginfo( 'version' );
		$data['server']         = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE']: '';

		return $data;

	}

}
```

#### Initialize the class:

```php
$tracker = new My_Tracker(
  'plugin-prefix',
  'Name of the plugin',
  '16 July 2016',
  '10',
  'project id goes here',
  'long write key goes here'
);

$tracker->init();```

If you've done everything correctly, you'll see a notice into the admin panel and once approved a new cron will be scheduled on the site.
