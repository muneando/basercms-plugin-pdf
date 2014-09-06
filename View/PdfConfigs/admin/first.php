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
<?php echo $this->BcForm->create('PdfConfig', array('action' => 'first')) ?>
<?php echo $this->BcForm->input('PdfConfig.active', array('type' => 'hidden', 'value' => '1')) ?>
<table cellpadding="0" cellspacing="0" class="form-table section" id="ListTable">
	<tr>
		<th class="col-head">
			はじめに<br />お読み下さい。
		</th>
		<td class="col-input">
			<strong>PDFリンク設定データ作成では、各コンテンツ用のPDFリンク設定データを作成します。</strong>
			<ul>
				<li>PDFリンク設定データがないコンテンツ用のデータのみ作成します。</li>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
	<?php echo $this->BcForm->submit('作成する', array(
		'div' => false,
		'class' => 'btn-red button',
		'id' => 'BtnSubmit',
		'onClick'=>"return confirm('PDFリンク設定データの作成を行いますが良いですか？')")) ?>
</div>
<?php echo $this->BcForm->end() ?>
