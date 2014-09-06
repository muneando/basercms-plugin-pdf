<?php
/**
 * Pdf プラグイン用
 * データベース初期化
 */
$this->Plugin->initDb('plugin', 'Pdf');

/**
 * ブログ記事情報を元にデータを作成する
 *   ・データがないブログ用のデータのみ作成する
 * 
 */
	$BlogPostModel = ClassRegistry::init('Blog.BlogPost');
	$dataList = $BlogPostModel->find('all', array('recursive' => -1));
	
	CakePlugin::load('Pdf');
	App::uses('Pdf', 'Pdf.Model');
	$PdfModel = new Pdf();
	
	if ($dataList) {
		foreach ($dataList as $key => $postData) {
			$existsData = $PdfModel->find('first', array('conditions' => array(
				'Pdf.content_id' => $postData['BlogPost']['id']
			)));
			$savaData = array();
			if (!$existsData) {
				$savaData['Pdf']['id'] = $postData['BlogPost']['blog_content_id'];
				$savaData['Pdf']['content_id'] = $postData['BlogPost']['id'];
				$PdfModel->create($savaData);
				$PdfModel->save($savaData, false);
			}
		}
	}
/**
 * ブログ情報を元にデータを作成する
 *   ・設定データがないブログ用のデータのみ作成する
 * 
 */
	$BlogContentModel = ClassRegistry::init('Blog.BlogContent');
	$blogContentDatas = $BlogContentModel->find('list', array('recursive' => -1));
	
	CakePlugin::load('Pdf');
	App::uses('PdfConfig', 'Pdf.Model');
	$PdfConfigModel = new PdfConfig();

	if ($blogContentDatas) {	
		foreach ($blogContentDatas as $key => $blog) {
			$PdfConfigData = $PdfConfigModel->find('first', array(
				'conditions' => array(
					'PdfConfig.content_id' => $key
				)
			));
			$savaData = array();
			if (!$PdfConfigData) {
				$savaData['PdfConfig']['id'] = $key;
				$savaData['PdfConfig']['content_id'] = $key;
				$savaData['PdfConfig']['status'] = 1;
				$savaData['PdfConfig']['priority'] = 1;
				$PdfConfigModel->create($savaData);
				$PdfConfigModel->save($savaData, false);
			}
		}
	}
