<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'wordpressbdd' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', 'root' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'm!N6%:La2O-wb}2z6 o{.ck?i&NI)iZ=-sBN|6~Iuiu+u~qbJb%mJ8v.{pXwB$~-');
  define('SECURE_AUTH_KEY',  '[y $,KS%QGh(5$M<J2$3fr1<gr:1IyVWKff*C8-dG:k6pdDz+!n1T8EebbaR5b]T');
  define('LOGGED_IN_KEY',    '5 p@ZXul14x<Cv3NT~#T#Thz|S|yCIZ+sdE2dvd(Do[ubS_|28u1?C[kgP;E7RW;');
  define('NONCE_KEY',        '5+E`H(tf^Qv~b>H%R?{ZBVk)YDaCbJ;=wd-f<K<[/#S;cQuw}/]-:@?URQ5dLj(Z');
  define('AUTH_SALT',        '4wLA:UlOcSYkob]}Q22lV])4,AG6{V4UNiqdF2GHhgL6+(=da,-XIh3+x!90:|?/');
  define('SECURE_AUTH_SALT', '%S@mTwDwaK^f{.L!I*vx;]`=6$v1_hoqr9-(D!-iOh>!Rw+oc@LMv|-V5FDhpUsL');
  define('LOGGED_IN_SALT',   '!}~qb~a}gFFm;=D%u})*zVQrEJV:f*KIB*hHqTrLxYNuV7f#o*.z9srIN/]7_7`U');
  define('NONCE_SALT',       '_sU;mC]_cVBd? /Tfx.pJdsCF>(e|[w$)rX{,t*+1jcYr&5gz3_Z/{ef/jNkrer3');
  
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'daawac69';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );

define('FS_METHOD', 'direct');