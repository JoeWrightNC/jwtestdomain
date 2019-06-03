<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'joe_wp108' );
/** MySQL database username */
define( 'DB_USER', 'joe_wp108' );
/** MySQL database password */
define( 'DB_PASSWORD', '!po3S514!F' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'bkk0699uvaw3wfhxrsgt7drmmqhhzfd71h8lgnujgdg7xfwcbqqzpsidgihtei6u' );
define( 'SECURE_AUTH_KEY',  'yjlpliqvmxwdanir0thnxbab5gbccjcspg4qq4oduwrzfehrv08dkgrng3ei98up' );
define( 'LOGGED_IN_KEY',    'thpgxxdcyxz0vuv2oyzoiia6fyscczhw3k2vivxj8hhoqrcn4nzdjxb7aszbjzso' );
define( 'NONCE_KEY',        'psmsh1lwq1menkbyrhqnyhmcdk9b9xdbwt5yz2xkylwbpxxsmvcjka5gbpejldgz' );
define( 'AUTH_SALT',        '3keupzmddlcofn3qtfkdgieovfh1etjtrkbyyzwfpim5kq5n0qtfhwsxvdhhcyi8' );
define( 'SECURE_AUTH_SALT', 'jcdwxons0vhmezvle04tg9nf4d1v9rrdytiskngtl6y4ctcvdefrbsebxqalizev' );
define( 'LOGGED_IN_SALT',   '5vqt3nf4plu61g8ipwtye4g5rsie0ab5wq21ncfqdvicylhry1fixn2ungk8ct0l' );
define( 'NONCE_SALT',       'fp94wsgg4nv8ev2bl3s9c7pzzljbrk0c9y2zmbcwnnp6u84dgr1df5krikkrbhek' );
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
// Enable WP_DEBUG mode
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
