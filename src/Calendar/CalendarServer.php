<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Calendar;

/**
 * Description of Calendar
 *
 * @author stephan
 */
class CalendarServer {

    private static $WP_CONFIG;
    private static $INSTNACE;
    public $dbname;
    private $pdo;
    public $base_uri;

    private function __construct() {
        self::$WP_CONFIG = str_replace('/wp-content/themes', '', get_theme_root()) . '/wp-config.php';
        include_once self::$WP_CONFIG;
        $this->dbname = DB_NAME;
        $this->base_uri = get_site_url() . '/club/caldav';
        $this->pdo = new \PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASSWORD);
    }

    public static function getInstance() {
        if (self::$INSTNACE == NULL) {
            self::$INSTNACE = new CalendarServer();
        }
        return self::$INSTNACE;
    }

    public function exception_error_handler($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        
    public function start() {

        set_error_handler(array(&$this, "exception_error_handler"));

// Backends
        $authBackend = new \Club\Sabre\Wordpress\WordpressUserBackend();
        $calendarBackend = new \Club\Sabre\CalDAV\Backend\PDO($this->pdo);
        $principalBackend = new \Club\Sabre\Wordpress\WordpressAcl($this->pdo);
// Directory structure
        $tree = [
            new \Sabre\CalDAV\Principal\Collection($principalBackend),
            new \Sabre\CalDAV\CalendarRoot($principalBackend, $calendarBackend),
        ];

        $server = new \Sabre\DAV\Server($tree);

        $server->setBaseUri($this->base_uri);

        /* Server Plugins */
        $authPlugin = new \Sabre\DAV\Auth\Plugin($authBackend);
        $server->addPlugin($authPlugin);

        $aclPlugin = new \Sabre\DAVACL\Plugin();
        $server->addPlugin($aclPlugin);

        /* CalDAV support */
        $caldavPlugin = new \Sabre\CalDAV\Plugin();
        $server->addPlugin($caldavPlugin);

        /* Calendar subscription support */
        $server->addPlugin(
                new \Sabre\CalDAV\Subscriptions\Plugin()
        );

        /* Calendar scheduling support */
        $server->addPlugin(
                new \Sabre\CalDAV\Schedule\Plugin()
        );

        /* WebDAV-Sync plugin */
        $server->addPlugin(new \Sabre\DAV\Sync\Plugin());

// Support for html frontend
        $browser = new \Sabre\DAV\Browser\Plugin();
        $server->addPlugin($browser);

// And off we go!
        $server->exec();
        wp_die();
    }

}
