<?php
/**
 * [HelperEventListener] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
class PdfHelperEventListener extends BcHelperEventListener {
/**
 * 登録イベント
 *
 */
	public $events = array(
		'Form.afterInput'
	);
	
/**
 * formAfterInput
 * 
 * @param CakeEvent $event
 * @return string
 */
	public function formAfterInput(CakeEvent $event) {
		$form = $event->subject();

		// ブログ設定：記事概要入力欄の下にPDFファイルの設定欄を表示する
		if($form->request->params['controller'] == 'blog_contents') {
			if($form->request->params['action'] == 'admin_add' || $form->request->params['action'] == 'admin_edit') {
				if($event->data['fieldName'] == 'BlogContent.use_content') {
					$event->data['out'] = $event->data['out'] . $form->element('Pdf.PdfConfigs_form');
				}
			}
		}
		
		// ブログ記事：説明文入力欄の下にPDFファイル名の入力欄を表示する
		if($form->request->params['controller'] == 'blog_posts') {
			if($form->request->params['action'] == 'admin_add' || $form->request->params['action'] == 'admin_edit') {
				if($event->data['fieldName'] == 'BlogPost.content') {
					$event->data['out'] = $event->data['out'] . $form->element('Pdf.Pdf_form');
				}
			}
		}
		return $event->data['out'];
	}
}
