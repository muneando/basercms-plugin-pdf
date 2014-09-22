<?php
/**
 * [ADMIN] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
/**
 * システムナビ
 */
if (BcUtil::isAdminUser()) {
	$config['BcApp.adminNavi.keyword'] = array(
			'name'		=> 'PDFリンク プラグイン',
			'contents'	=> array(
					array('name' => 'PDF設定データ作成',
							'url' => array(
									'admin' => true,
									'plugin' => 'pdf',
									'controller' => 'pdf_configs',
									'action' => 'first')
					)
			)
	);
}
/**
 * ラベル設定
 */
$config['Pdf.label.name'] = 'カスタムテキスト';

$config['Pdf.upload_dir'] = APP . 'Upload' . DS . 'pdf' . DS;

