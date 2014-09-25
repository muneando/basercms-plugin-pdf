<?php
/**
 * PDFヘルパー
*
* baserCMS :  Based Website Development Project <http://basercms.net>
* Copyright 2008 - 2014, baserCMS Users Community <http://sites.google.com/site/baserusers/>
*
* @copyright		Copyright 2008 - 2014, baserCMS Users Community
* @link			http://basercms.net baserCMS Project
* @package			Blog.View.Helper
* @since			baserCMS v 0.1.0
* @license			http://basercms.net/license/index.html
*/

/**
 * PDFヘルパー
* @package Blog.View.Helper
*/
class PdfHelper extends AppHelper {

	/**
	 * ヘルパー
	 *
	 * @var array
	 * @access public
	 */
	public $helpers = array('Html', 'BcTime', 'BcBaser', 'BcUpload');

	/**
	 * ブログカテゴリモデル
	 *
	 * @var BlogCategory
	 * @access public
	 */
	public $BlogCategory = null;
	
	/**
	 * コンストラクタ
	 *
	 * @return void
	 * @access public
	 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->setContent();
	}
	
	/**
	 * ブログコンテンツデータをセットする
	 *
	 * @param int $blogContentId
	 * @return void
	 * @access protected
	 */
	public function setContent($blogContentId = null) {
		if (isset($this->blogContent) && !$blogContentId) {
			return;
		}
		if ($blogContentId) {
			$BlogContent = ClassRegistry::getObject('BlogContent');
			$BlogContent->expects(array());
			$this->blogContent = Hash::extract($BlogContent->read(null, $blogContentId), 'BlogContent');
		} elseif (isset($this->_View->viewVars['blogContent']['BlogContent'])) {
			$this->blogContent = $this->_View->viewVars['blogContent']['BlogContent'];
		}
		if ($this->blogContent) {
			$BlogPost = ClassRegistry::init('Blog.BlogPost');
			$BlogPost->setupUpload($this->blogContent['id']);
		}
	}
	/**
	 * 記事のタイトルを出力する
	 *
	 * @param array $post
	 * @return void
	 */
	public function postTitle($post, $link = true, $options = array()) {
		echo $this->getPostTitle($post, $link, $options);
	}
	
	/**
	 * 記事タイトルを取得する
	 *
	 * @param array $post
	 * @param boolean $link
	 * @return string
	 * @access public
	 */
	public function getPostTitle($post, $link = true, $options = array()) {
		if ($link) {
			return $this->getPostLink($post, $post['BlogPost']['name'], $options);
		} else {
			return $post['BlogPost']['name'];
		}
	}
	
	/**
	 * 記事へのリンクを取得する
	 *
	 * @param array $post
	 * @param string $title
	 * @param array $options
	 * @return string
	 * @access public
	 */
	public function getPostLink($post, $title, $options = array()) {
		$url = array('admin' => false, 'plugin' => 'pdf', 'controller' => 'pdf', 'action' => 'show', $post['BlogPost']['blog_content_id'], $post['BlogPost']['id']);
		return $this->BcBaser->getLink($title, $url, $options);
	}
    
    /**
     * ファイル名からファイル種別（PDF or HTML）かを判定する。
     * 
     * @param type $fileName
     * @return string
     */
    public function getFileType($fileName) {
		$pathInfo = pathinfo($fileName);
		$extension = trim($pathInfo['extension']);
		if ($extension === 'pdf') {
			return 'pdf';
		}
		if ($extension === 'html' || $extension === 'htm') {
			return 'html';
		}
		
        
    
    }
}
