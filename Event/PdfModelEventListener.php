<?php
/**
 * [ModelEventListener] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */

class PdfModelEventListener extends BcModelEventListener {
/**
 * 登録イベント
 *
 */
	public $events = array(
		'Blog.BlogPost.beforeFind',
		'Blog.BlogPost.afterDelete',
		'Blog.BlogPost.beforeValidate',
		'Blog.BlogContent.beforeFind',
		'Blog.BlogContent.afterDelete',
		'Blog.BlogContent.afterSave'
	);
	
/**
 * キーワードモデル
 * 
 * @var Object
 */
	public $PdfModel = null;
	
/**
 * キーワード設定モデル
 * 
 * @var Object
 */
	public $PdfConfigModel = null;
	

/**
 * blogBlogPostBeforeFind
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostBeforeFind(CakeEvent $event) {
		$model = $event->subject();
		$association = array(
			'Pdf' => array(
				'className' => 'Pdf.Pdf',
				'foreignKey' => 'id'
			)
		);
		$model->bindModel(array('hasOne' => $association));
	}

/**
 * blogBlogContentBeforeFind
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentBeforeFind(CakeEvent $event) {
		$model = $event->subject();
		$association = array(
			'PdfConfig' => array(
				'className' => 'Pdf.PdfConfig',
				'foreignKey' => 'id'
			)
		);
		$model->bindModel(array('hasOne' => $association));
	}

/**
 * blogBlogPostAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogPostAfterDelete(CakeEvent $event) {
		$model = $event->subject();
		// ブログ記事削除時、そのコンテンツが持つキーワード情報を削除する
		$PdfModel = ClassRegistry::init('Pdf.Pdf');
		$data = $PdfModel->find('first', array('conditions' => array(
			'Pdf.content_id' => $model->id
		)));			
		if($data) {
			if(!$PdfModel->delete($data['Pdf']['id'])) {
				$this->log('ID:' . $data['Pdf']['id'] . 'のキーワードの削除に失敗しました。');
			}
		}
		return true;
	}
	
/**
 * blogBlogContentAfterDelete
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterDelete(CakeEvent $event) {
		$model = $event->subject();
		// ブログ設定削除時、そのコンテンツが持つキーワード設定情報を削除する
		$PdfConfigModel = ClassRegistry::init('Pdf.PdfConfig');
		$data = $PdfConfigModel->find('first', array('conditions' => array(
			'PdfConfig.content_id' => $model->id
		)));
		if ($data) {
			if(!$PdfConfigModel->delete($data['PdfConfig']['id'])) {
				$this->log('ID:' . $data['PdfConfig']['id'] . 'のキーワード設定の削除に失敗しました。');
			}
		}
		return true;
	}
	

/**
 * blogBlogPostBeforeValidate
 * 
 * @param CakeEvent $event
 * @return boolean or array
 */
	public function blogBlogPostBeforeValidate(CakeEvent $event) {
		$model = $event->subject();
		// ブログ記事保存の手前で Pdf モデルのデータに対して validation を行う
		$PdfModel = ClassRegistry::init('Pdf.Pdf');
		$PdfModel->set($model->data);
		return $PdfModel->validates();
	}
	
/**
 * blogBlogContentAfterSave
 * 
 * @param CakeEvent $event
 */
	public function blogBlogContentAfterSave(CakeEvent $event) {
		$model = $event->subject();
		$created = $event->data[0];
		if ($created) {
			$contentId = $model->getLastInsertId();
		} else {
			$contentId = $model->data['BlogContent']['id'];
		}
		$saveData = $this->_generateContentSaveData($model, $contentId);
		if (!$this->PdfConfigModel->save($saveData)) {
			$this->log(sprintf('ID：%s のキーワード設定の保存に失敗しました。', $model->data['PdfConfig']['id']));
		}
	}

/**
 * 保存するデータの生成
 *
 * @param Object $model        	
 * @param int $contentId        	
 * @return array
 */
	private function _generateContentSaveData($model, $contentId) {
		$this->PdfConfigModel = ClassRegistry::init ( 'Pdf.PdfConfig' );
		$data = array ();
		
		$data = $this->PdfConfigModel->find ( 'first', array (
				'conditions' => array (
						'PdfConfig.id' => $contentId 
				) 
		) );
		
		if (empty ( $data )) {
			// 追加時
			$this->PdfConfigModel->create ();
			
			$data ['PdfConfig'] ['id'] = $contentId;
			$data ['PdfConfig'] ['content_id'] = $contentId;
			$data ['PdfConfig'] ['status'] = 1;
			$data ['PdfConfig'] ['priority'] = 1;
		} else {
			debug ( $data );

			$data ['PdfConfig'] ['id'] = $contentId;
			$data ['PdfConfig'] ['content_id'] = $contentId;
		
			if (! empty ( $model->data ['PdfConfig'] ['status'] )) {
				$data ['PdfConfig'] ['status'] = $model->data ['PdfConfig'] ['status'];
			} elseif ($model->data ['PdfConfig'] ['status'] === '0') {
				$data ['PdfConfig'] ['status'] = 0;
			} else {
				$data ['PdfConfig'] ['status'] = 1;
			}
			if (! empty ( $model->data ['PdfConfig'] ['priority'] )) {
				$data ['PdfConfig'] ['priority'] = $model->data ['PdfConfig'] ['priority'];
			} elseif ($model->data ['PdfConfig'] ['priority'] === '0') {
				$data ['PdfConfig'] ['priority'] = 0;
			} else {
				$data ['PdfConfig'] ['priority'] = 1;
			}
		}
		
		return $data;
	}
	
/**
 * HTMLタグを除去する
 * 
 * @param string $str
 * @return string
 */
	protected function _washText($str = '') {
		return strip_tags($str);
	}
	
}
