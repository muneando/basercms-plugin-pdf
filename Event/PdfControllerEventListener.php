<?php
/**
 * [ControllerEventListener] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
class PdfControllerEventListener extends BcControllerEventListener {
/**
 * 登録イベント
 *
 */
	public $events = array(
		'initialize',
		'Blog.Blog.beforeRender'
	);
	
/**
 * キーワードモデル
 * 
 * @var Object
 */
	public $PdfModel = null;
	
/**
 * initialize
 * 
 * @param CakeEvent $event
 */
	public function initialize(CakeEvent $event) {
		$controller = $event->subject();
		// Pdfヘルパーの追加
		// XXX preview時の反映も必要なため、この位置で追加しないと利用できない
		$controller->helpers[] = 'Pdf.Pdf';
	}
	

/**
 * blogBlogBeforeRender
 * 
 * @param CakeEvent $event
 * @return void
 */
	public function blogBlogBeforeRender(CakeEvent $event) {
		$controller = $event->subject();
		// ブログ記事表示画面で実行
		if (!BcUtil::isAdminSystem()) {
			if ($controller->request->params['action'] == 'archives') {
				if (count($controller->request->params['pass']) == '1') {
					if (!empty($controller->viewVars['post'])) {
						// ブログ記事のデータを元に、キーワードデータを取得する
						$Pdf = $controller->BlogPost->Pdf->find('first', array('conditions' => array(
							'Pdf.content_id' => $controller->viewVars['post']['BlogPost']['id']
						)));
						if ($Pdf) {
							$controller->viewVars['Pdfs'] = $Pdf['Pdf']['Pdfs'];
							$controller->viewVars['Pdf'] = $Pdf['Pdf'];
						}
					}
				}
			}
		} else {
			// 管理側でプレビューの際に入力中のキーワードデータを送る
			// XXX 固定ページのプレビューの場合、pagesBeforeRender がそのまま反映されるためそちらは処理不要
			if ($controller->preview) {
				if (!empty($controller->request->data['Pdf'])) {
					$controller->viewVars['Pdf'] = $controller->request->data['Pdf'];
				}
			}
		}
	}
	
}
