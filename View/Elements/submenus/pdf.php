<?php
/**
 * [ADMIN] Pdf
 *
 * @link			https://github.com/muneando/basercms-plugin-pdf
 * @author			mune.ando
 * @package			Pdf
 * @license			MIT
 */
?>
<tr>
	<th>キーワード設定管理メニュー</th>
	<td>
		<ul>
			<?php if(!$judgePdfConfigUse): ?>
			<li><?php $this->BcBaser->link('キーワード設定データ作成', array('admin' => true, 'plugin' => 'pdf', 'controller' => 'pdf_configs', 'action'=>'first')) ?></li>
			<?php endif ?>
		</ul>
	</td>
</tr>
