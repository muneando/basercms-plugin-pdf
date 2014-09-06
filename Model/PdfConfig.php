<?php
/**
 * [Model] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
class PdfConfig extends BcPluginAppModel {
/**
 * ModelName
 * 
 * @var string
 */
	public $name = 'PdfConfig';
	
/**
 * PluginName
 * 
 * @var string
 */
	public $plugin = 'Pdf';
	
/**
 * actsAs
 * 
 * @var array
 */
	public $actsAs = array('BcCache');
	
/**
 * 初期値を取得する
 *
 * @return array
 */
	public function getDefaultValue() {
		$data = array(
			'PdfConfig' => array(
				'status' => false
			)
		);
		return $data;		
	}
	
}
