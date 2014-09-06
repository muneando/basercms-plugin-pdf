<?php
/**
 * [Helper] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
class PdfBaserHelper extends AppHelper {
/**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('Pdf', 'BcBaser', 'Html');
	
	
	/**
	 * PDFファイルにリンクするブログ記事一覧出力
	 * ページ編集画面等で利用する事ができる。
	 * 利用例: <?php $this->BcBaser->blogPosts('news', 3) ?>
	 * ビュー: app/webroot/theme/{テーマ名}/blog/{コンテンツテンプレート名}/posts.php
	 *
	 * @param int $contentsName
	 * @param int $num
	 * @param array $options
	 * @param mixid $mobile '' / boolean
	 * @return void
	 * @access public
	 */
	public function pdfTilteList($contentsName, $num = 5, $options = array()) {
		$options = array_merge(array(
				'category' => null,
				'tag' => null,
				'year' => null,
				'month' => null,
				'day' => null,
				'id' => null,
				'keyword' => null,
				'template' => null,
				'direction' => null,
				'page' => null,
				'sort' => null
		), $options);
	
		$BlogContent = ClassRegistry::init('Blog.BlogContent');
		$id = $BlogContent->field('id', array('BlogContent.name' => $contentsName));
		$url = array('admin' => false, 'plugin' => 'pdf', 'controller' => 'pdfConfigs', 'action' => 'posts');
	
		$settings = Configure::read('BcAgent');
		foreach ($settings as $key => $setting) {
			if (isset($options[$key])) {
				$agentOn = $options[$key];
				unset($options[$key]);
			} else {
				$agentOn = (Configure::read('BcRequest.agent') == $key);
			}
			if ($agentOn) {
				$url['prefix'] = $setting['prefix'];
				break;
			}
		}

		echo $this->requestAction($url, array('return', 'pass' => array($id, $num), 'named' => $options));
	}
}
