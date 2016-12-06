<?php
/**
 * Kapselt eine Datenbankverbindung
*
* @author Sebastian Schinkinger
*
*/
class DBConnection {

	private $conn;
	/** Singleton-Instanz der Klasse */
	private static $instance;

	/** Schalter um Fehlerausgaben dargestellt zu bekommen */
	private $debugMode;

	/**
	 * Singleton Zugriff
	 *
	 * @return Singleton-Instanz
	 */
	public static function getInstance() {

		// Pr�ft ob die Instanz bereits erstellt wurde
		if (!isset(self::$instance)) {
			// da noch keine Instanz vorhanden ist, wird eine Neue erstellt und gespeichert
			self::$instance = new DBConnection();
		}

		return self::$instance;

	}

	public function __construct() {

		// l�d die Verbinungsdaten aus der dbConnStr.lib.php-Datei
		include("dbConnStr.lib.php");

		// �ffnet eine neue Datenbankverbindung und speichert diese intern
		$this->conn = pg_connect($connStr);

		$debugMode = true;
	}

	public function __destruct() {
		if (is_resource($this->conn)) {pg_close($this->conn);}
	}

	/**
	 * Gibt die konkrete Resource der intern gekapselten Datenbankverbindung zur�ck
	 */
	public function getConnection() {
		return $this->conn;
	}

	/**
	 * Gibt zur�ck, ob die Verbindung sich im Debug-Modus befindet oder nicht
	 */
	public function isDebugMode() {
		return $this->debugMode;
	}

	/**
	 * Setzt den Debug-Modus dieser Verdbindung. Ist dieser "true" werden Fehlermeldungen ausgegeben,
	 * andernfalls nicht.
	 */
	public function setDebugMode($debugMode) {
		$this->debugMode = $debugMode;
	}
}
