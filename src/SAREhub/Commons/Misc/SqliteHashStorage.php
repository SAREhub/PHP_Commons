<?php


namespace SAREhub\Commons\Misc;

/**
 * Simple implemntation of HashStorage, where hashes are store in sqlite3 database
 */
class SqliteHashStorage implements HashStorage {
	
	/** @var string */
	private $filePath;
	
	/** @var string */
	private $tableName;
	
	/** @var bool */
	private $firstTime = false;
	
	/** @var \SQLite3 */
	private $storage = null;
	
	/**
	 * @param $filePath Database file path
	 * @param string $tableName table name in database for store hashes
	 */
	public function __construct($filePath, $tableName = 'hashes') {
		$this->filePath = $filePath;
		$this->tableName = $tableName;
	}
	
	public function open() {
		if ($this->isOpened()) {
			return;
		}
		
		$this->firstTime = !file_exists($this->filePath);
		
		$this->storage = new \SQLite3($this->filePath);
		$this->storage->exec("PRAGMA journal_mode = OFF;");
		$this->storage->exec("PRAGMA synchronous = NORMAL;");
		$this->storage->exec("PRAGMA locking_mode = EXCLUSIVE;");
		$this->storage->exec("PRAGMA automatic_index = false;");
		
		if ($this->firstTime) {
			$createTableSql = "CREATE TABLE ".$this->tableName."(id TEXT PRIMARY KEY, hash TEXT NOT NULL) WITHOUT ROWID;";
			$this->storage->exec($createTableSql);
		}
	}
	
	public function close() {
		if ($this->isOpened()) {
			$this->storage->close();
			$this->storage = null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function isOpened() {
		return $this->storage !== null;
	}
	
	/**
	 * @param string $id
	 * @return string
	 */
	public function findById($id) {
		return $this->storage->querySingle("SELECT hash FROM ".$this->tableName." WHERE id='$id'");
	}
	
	/**
	 * Inserts hashes in batch for better performance
	 * @param array $hashes 'id' => 'hash'
	 */
	public function insertMulti(array $hashes) {
		$valuesString = '';
		foreach ($hashes as $id => $hash) {
			$valuesString .= "('$id','$hash'),";
		}
		
		$this->storage->exec("INSERT INTO ".$this->tableName."(id, hash) VALUES ".rtrim($valuesString, ','));
	}
	
	/**
	 * Updating hashes in batch for better performance
	 * @param array $hashes 'id' => 'hash'
	 */
	public function updateMulti(array $hashes) {
		$startSql = "UPDATE ".$this->tableName;
		$this->storage->exec('BEGIN TRANSACTION');
		foreach ($hashes as $id => $hash) {
			$this->storage->exec($startSql." SET hash='$hash' WHERE id='$id'");
		}
		$this->storage->exec('END TRANSACTION');
	}
	
	
}