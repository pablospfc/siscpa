<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'siscpa');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '80khs1'); 

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0e~hdPCfu:XQHNENhk&Ziob?dv?~b9/XaPff%EhXH>6lVVr=Vx)qWDj1br/Ba6,k');
define('SECURE_AUTH_KEY',  'bam<GLivvO!}MX29L;:S>v|:&T4}$vur_&.CT5vv;-M%73aq[[+hV@YRg$b+s>am');
define('LOGGED_IN_KEY',    '*88TH[ESWW^^JdByZ_ogvrZSICL~l|w(3UKL}[e1wbuS}LxT$2}*YeGgHC9MfU3j');
define('NONCE_KEY',        '/q}hC0]Q>-ETlGnKdQc[7t0o+y[$o*`JF&^syDV.#l2/Y%q#>|@~_geEh3a`@5&?');
define('AUTH_SALT',        'VK[nYtM$o|Zdfed*Z,9G<e%Bu0/vdxaNq|0W|jG_==/!DRMi|]pXhz1kCb(mF-~b');
define('SECURE_AUTH_SALT', '9I9(xm:H_wsUIq>!f>xrKDv7l,;)Wowoxo&F9^[3a{]t5*qwA9 9q$kj7*e T&ii');
define('LOGGED_IN_SALT',   'YhNBWh)+jWU2PoqZ4=ft->e`oiW)Se$:k_ kNP>An5wmluUvI_;ugnINo~0NvZao');
define('NONCE_SALT',       'J%H#}:Yug61}D Ew~+sS+OpT7}eq^0*D@m,YaEzt{C*8fpz]9=_n{u%&}Gs^$id~');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');

