<?php
/**
 * [Model] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
class Pdf extends BcPluginAppModel {
/**
 * モデル名
 * 
 */
	public $name = 'Pdf';
	
/**
 * プラグイン名
 * 
 */
	public $plugin = 'Pdf';
	
/**
 * actsAs
 * 
 * @var array
 */
	public $actsAs = array('BcCache');
	
	/**
	 * バリデーション
	 */
	public $validate = array (
			'pdf_file' => array (
					// 拡張子を配列で定義
					'extension' => array (
							'rule' => array (
									'extension',
									array (
											'pdf' 
									) 
							),
							'message' => array (
									'PDFファイルを指定してください。' 
							),
							'allowEmpty' => true
					),
			) 
	);
}
