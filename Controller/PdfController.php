<?php
/**
 * [Controller] Pdf
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
class PdfController extends PdfAppController {
/**
 * ControllerName
 * 
 * @var string
 */
	public $name = 'Pdf';
	
/**
 * Model
 * 
 * @var array
 */
	public $uses = array('Pdf.Pdf', 'Pdf.PdfConfig', 'Blog.BlogPost', 'Blog.BlogContent');
	
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
		array('name' => 'ブログ管理', 'url' => array('plugin' => 'blog', 'controller' => 'blog_contents', 'action' => 'index'))
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
				'show'
		);
		
		$this->BlogContent->recursive = -1;
		if ($this->contentId) {
			$this->blogContent = $this->BlogContent->read(null, $this->contentId);
		} else {
			$this->blogContent = $this->BlogContent->read(null, $this->params['pass'][0]);
		}
		$this->crumbs[] = array('name' => $this->blogContent['BlogContent']['title'] . '管理', 'url' => array('plugin' => 'blog','controller' => 'blog_posts', 'action' => 'index', $this->request->params['pass'][0]));
		}

/**
 * [ADMIN] 編集
 * 
 * @param int $id
 * @return void
 */
	public function admin_edit($content_id, $id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));			
		}
		$this->_setBlogPostDatas($id);
		if (empty($this->request->data)) {
			$this->{$this->modelClass}->id = $id;
			$this->request->data = $this->{$this->modelClass}->read();
		} else {
			if(empty($this->request->data['Pdf']['pdf_upload_file']['name'])) {
				unset($this->request->data['Pdf']['pdf_file']);
			} else {
				$this->request->data['Pdf']['pdf_file'] = $this->request->data['Pdf']['pdf_upload_file']['name'];
			}
			$this->{$this->modelClass}->set($this->request->data);

			if ($this->{$this->modelClass}->save($this->request->data)) {
				
				// ファイルのアップロード
				if(!empty($this->request->data['Pdf']['pdf_upload_file']['name'])) {
					$pdfFile = $this->request->data['Pdf']['pdf_upload_file'];
					if(in_array($pdfFile['error'], array(1,2))) {
						$this->setMessage('PDFファイルをアップロードしたときに、何か障害が発生しました。', true);
					}
					if(is_uploaded_file($pdfFile['tmp_name'])) {
						$imagePath = Configure::read('Pdf.upload_dir');
						debug($pdfFile);
						if (move_uploaded_file($pdfFile["tmp_name"],  $imagePath . $pdfFile['name'] )) {
							chmod($imagePath . $pdfFile['name'], 0644);
						} else {
							$this->setMessage('PDFファイルをアップロードできませんでした。', true);
						}
					}
				}
				$this->setMessage('更新が完了しました。');
				$this->redirect(array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'edit', $content_id, $id));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		$this->crumbs[] = array(
				'name' => '[' . $this->blogContent['BlogContent']['title'] . '] 記事編集： ' . $this->blogPostDatas['BlogPost']['name'],
				'url' => array('plugin' => 'blog','controller' => 'blog_posts', 'action' => 'edit', $content_id, $id)
		);
		
		$this->set('blogContentDatas', array('0' => '固定ページ') + $this->blogContentDatas);
		$this->set('blogPostDatas', $this->blogPostDatas);
		$this->set('contentId', $content_id);
		$this->pageTitle = '「'. $this->blogPostDatas['BlogPost']['name'] . '」のPDFファイル';
		$this->render('form');
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
 * PDFファイルを出力する。
 * @param int $blogPostId
 * @throws Exception
 */
	public function show($contentId, $blogPostId) {
		if (!empty($this->params['named']['template'])) {
			$template = $this->request->params['named']['template'];
		} else {
			$template = 'show';
		}
		try {
			// 管理ページからは無条件に表示する。
			if ($this->action == 'admin_show') {
				$conditions = array(
						'BlogPost.id' => $blogPostId
						);
			} else {
				$conditions = array(
						'BlogPost.id' => $blogPostId,
						'BlogContent.status' => 1,
						'PdfConfig.status' => 1,
				);
				$conditions = am($conditions, $this->BlogPost->getConditionAllowPublish());
			}

			$blogPost = $this->BlogPost->find(
					'first',
					array(
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
							)
					);

			if(empty($blogPost)) throw new Exception('PDFファイルが見つかりませんでした。');
			$pdfConfig = $this->PdfConfig->read(null, $contentId);
			
			$blogUrl = '/' . $blogPost['BlogContent']['name'] . '/archives/' . $blogPost['BlogPost']['no'];
			
			// 管理ページからは無条件にPDFを表示する。
			if ($this->action == 'admin_show') {
				$showType = 'PDF';
			} elseif ($pdfConfig['PdfConfig']['priority'] == 1) {
				if( $blogPost['Pdf']['pdf_file'] ){
					$showType = 'PDF';
				} else {
					$showType = 'HTML';
				}
			} else {
				if( !empty($blogPost['BlogPost']['detail'])){
					$showType = 'HTML';
				} else {
					if( $blogPost['Pdf']['pdf_file'] ){
						$showType = 'PDF';
					} else {
						$showType = 'HTML';
					}
				}
			}
			
			if($showType == 'PDF') {
				$this->_show($blogPost['Pdf']['pdf_file']);
				return;
			} if($showType == 'HTML') {
				$this->redirect($blogUrl);
			} else {
				throw new Exception('表示できませんでした。');
			}
	
		} catch (Exception $e) {
			$this->setMessage($e->getMessage(), true);
		}
		$this->render($this->blogContent['BlogContent']['template'] . DS . $template);
	}
	
/**
 * [ADMIN] PDFファイルを出力する
 * 
 * @param int $contentId
 * @param int $blogPostId
 */
 	public function admin_show($contentId, $blogPostId) {
		$this->show($contentId, $blogPostId);
	}

/**
 * ファイルの内容を表示する。
 * 
 * @param string $fileName
 * @throws Exception
 */
	private function _show($fileName) {
		switch(trim(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)))) {
			case 'pdf':
				$contentType = 'application/pdf';
				break;
			case 'html':
			case 'htm':
				$contentType = 'text/html';
				break;
			default:
				throw new Exception('コンテンツタイプが見つかりませんでした。');
		}
		
		$this->autoRender = false; // Viewを使わないように
		Configure::write('debug', 0); // debugコードを出さないように
		$path_name = Configure::read('Pdf.upload_dir') . $fileName;
		if( is_file($path_name) ){
			$fp = fopen($path_name, 'rb');
			$pdf = fread($fp, filesize($path_name));
			header ("Content-type: $contentType;"); 
			header("Content-disposition: inline; filename=" . $fileName);
		} else {
			throw new Exception('ファイルが見つかりませんでした。');
		}
		print($pdf); // 出力
		
	}
}