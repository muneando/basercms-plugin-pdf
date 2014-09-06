<?php
/**
 * [ViewEventListener] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
class PdfViewEventListener extends BcViewEventListener {
/**
 * 登録イベント
 * 
 * @var array
 */
	public $events = array(
		'Blog.BlogPosts.beforeRender'
	);

/**
 * blogBlogPostBeforeRender
 * 
 * @param CakeEvent $event
 * @return void
 */
	public function blogBlogPostsBeforeRender(CakeEvent $event) {
		$View = $event->subject();
		if (BcUtil::isAdminSystem()) {
			if ($View->request->params['action'] == 'admin_add' || $View->request->params['action'] == 'admin_edit') {
				$PdfConfig = ClassRegistry::init('Pdf.PdfConfig');
				$data = $PdfConfig->find('first', array('conditions' => array(
					'PdfConfig.content_id' => $View->viewVars['blogContent']['BlogContent']['id']
				)));
				if ($data) {
					$View->request->data['PdfConfig'] = $data['PdfConfig'];
				}
			}
		}
	}
}
