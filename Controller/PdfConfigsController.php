<?php
/**
 * [Controller] PdfConfigs
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
/**
 * Include files
 */
App::import('Controller', 'Pdf.PdfApp');
class PdfConfigsController extends PdfAppController {
/**
 * ControllerName
 * 
 * @var string
 */
	public $name = 'PdfConfigs';
	
/**
 * Model
 * 
 * @var array
 */
	public $uses = array('Pdf.PdfConfig', 'Blog.BlogPost', 'Blog.BlogContent');
	
/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth', 'Cookie');

/**
 * ぱんくずナビ
 *
 * @var string
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
	);
	
/**
 * 管理画面タイトル
 *
 * @var string
 */
	public $adminTitle = 'PDFリンク設定';
	
/**
 * beforeFilter
 *
 * @return	void
 */
	public function beforeFilter() {		
		parent::beforeFilter();
		
		/* 認証設定 */
		$this->BcAuth->allow(
				'posts'
		);

		$this->BlogContent->recursive = -1;
		if ($this->contentId) {
			$this->blogContent = $this->BlogContent->read(null, $this->contentId);
		} elseif (isset($this->params['pass'][0])) {
			$this->blogContent = $this->BlogContent->read(null, $this->params['pass'][0]);
		} else {
			$this->blogContent = array();
		}
	}
	
/**
 * [ADMIN] 設定一覧
 * 
 * @return void
 */
	public function admin_index() {
		$this->pageTitle = $this->adminTitle . '一覧';
		$this->search = 'Pdf_configs_index';
		$this->help = 'Pdf_configs_index';
		parent::admin_index();
	}
	
/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->pageTitle = $this->adminTitle . '編集';
		parent::admin_edit($id);
	}
	
/**
 * [ADMIN] 削除
 *
 * @param int $id
 * @return void
 */
	public function admin_delete($id = null) {
		parent::admin_delete($id);
	}
	
/**
 * [ADMIN] 各ブログ別のPDFリンク設定データを作成する
 *   ・PDFリンク設定データがないブログ用のデータのみ作成する
 * 
 * @return void
 */
	public function admin_first() {
		if ($this->request->data) {
			$count = 0;
			if ($this->blogContentDatas) {
				foreach ($this->blogContentDatas as $key => $blog) {	
					$configData = $this->PdfConfig->findByContentId($key);
					if (!$configData) {
						$this->request->data['PdfConfig']['id'] = $key;
						$this->request->data['PdfConfig']['content_id'] = $key;
						$this->request->data['PdfConfig']['status'] = 1;
						$this->request->data['PdfConfig']['priority'] = 1;
						$this->PdfConfig->create($this->request->data);
						if (!$this->PdfConfig->save($this->request->data, false)) {
							$this->log(sprintf('ブログID：%s の登録に失敗しました。', $key));
						} else {
							$count++;
						}
					}
				}
			}
			
			$message = sprintf('%s 件のPDFリンク設定を登録しました。', $count);
			$this->setMessage($message, false, true);
		}

		$this->pageTitle = $this->adminTitle . 'データ作成';
	}

	/**
	 * 記事リストを出力
	 * requestAction用
	 *
	 * @param int $blogContentId
	 * @param mixed $num
	 * @access public
	 */
	public function posts($blogContentId, $limit = 5) {
		if (!empty($this->params['named']['template'])) {
			$template = $this->request->params['named']['template'];
		} else {
			$template = 'posts';
		}
		unset($this->request->params['named']['template']);
	
		$this->layout = null;
		$this->contentId = $blogContentId;
		
		$conditions = array(
				'BlogContent.status' => 1,
				'PdfConfig.status' => 1,
				'BlogPost.blog_content_id' => $blogContentId
		);
		$conditions = am($conditions, $this->BlogPost->getConditionAllowPublish());
		
		if(isset($this->request->params['named']['year'])) {
			$conditions["YEAR(BlogPost.posts_date)"] = $this->request->params['named']['year'];
		}
		$params = array(
						'conditions' => $conditions,
						'joins' => array(
								array('table' => 'pdf_configs',
										'alias' => 'PdfConfig',
										'type' => 'inner',
										'conditions' => array(
												'BlogContent.id = PdfConfig.id'
										)
								),			
							),
						'order' => array('posts_date DESC'),
						'limit' => $limit
				);
		if ($limit > 0) {
			$params['limit'] = $limit;
		}
		
		$datas = $this->BlogPost->find(
				'all',
				$params
		);
		$this->set('posts', $datas);
		$this->render($this->blogContent['BlogContent']['template'] . DS . $template);
	}
	
	/**
	 * 一覧用の検索条件を生成する
	 *
	 * @param array $data
	 * @return array $conditions
	 */
	public function _createAdminIndexConditions($data) {
	
		$conditions = array();
		$model = '';
	
		if (isset($data[$this->modelClass]['model'])) {
			$model = $data[$this->modelClass]['model'];
		}
		if (isset($data[$this->modelClass]['status']) && $data[$this->modelClass]['status'] === '') {
			unset($data[$this->modelClass]['status']);
		}
		if (isset($data[$this->modelClass]['custom']) && $data[$this->modelClass]['custom'] === '') {
			unset($data[$this->modelClass]['custom']);
		}
	
		unset($data['_Token']);
		unset($data[$this->modelClass]['model']);
	
		// 条件指定のないフィールドを解除
		foreach($data[$this->modelClass] as $key => $value) {
			if ($value === '') {
				unset($data[$this->modelClass][$key]);
			}
		}
	
		if ($data[$this->modelClass]) {
			$conditions = $this->postConditions($data);
		}
	
		if ($model) {
			$conditions['and'] = array(
					$this->modelClass .'.model' => $model
			);
		}
	
		if($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}
	
	public function ajax_postsForYear($blogContentId, $year) {
		$this->layout = 'ajax';		
		$options = array('year' => $year);
		$url = array('admin' => false, 'plugin' => 'pdf', 'controller' => 'pdfConfigs', 'action' => 'posts');
		$this->set('element', $this->requestAction($url, array('pass' => array($blogContentId, 0), 'named' => $options)));
	}
}