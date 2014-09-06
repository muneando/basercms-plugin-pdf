<?php 
class PdfConfigsSchema extends CakeSchema {

	public $file = 'pdf_configs.php';

	public $connection = 'plugin';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $pdf_configs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'key' => 'primary'),
		'content_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'status' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		'priority' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
