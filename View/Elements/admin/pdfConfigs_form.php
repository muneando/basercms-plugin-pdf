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

<?php
	$statuses = array(0 => '無効', 1 => '有効');
	$priorites = array( 0 => 'HTML', 1 => 'PDFファイル');
	$script = '
$(document).ready(function(){
	
	// PDFファイル削除ボタンクリック時イベント
	$(".btn-delete").click(function(){
		if(window.confirm("PDFファイルを本当に削除してもよろしいですか？\n※ 削除したPDFファイルは元に戻すことができません。")){
			$.ajax({
				type: "POST",
				url: $(".btn-delete").attr("href"),
				success: function(result){
					if(result) {
						$(".pdf-file-name").html("");
					} else {
						alert("PDFファイル削除に失敗しました。");
					}
				}
			});
 		}

		return false;

	});
});
	';
$this->Html->scriptBlock($script, array('inline' => false));
 ?>	
 
<tr>
	<th class="col-head"><?php echo $this->BcForm->label('PdfConfig.title', 'PDFファイル公開') ?></th>
	<td class="col-input">
		<?php echo $this->BcForm->input('PdfConfig.status', array('type' => 'radio', 'options' => $statuses)) ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpPdfStatus', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
		<?php echo $this->BcForm->error('PdfConfig.status') ?>
		<div id="helpPdfStatus" class="helptext">PDFファイルの表示を有効にするかを設定します。</div>
	</td>
</tr>
<tr>
	<th class="col-head"><?php echo $this->BcForm->label('PdfConfig.title', 'PDFファイル優先') ?></th>
	<td class="col-input">
		<?php echo $this->BcForm->input('PdfConfig.priority', array('type' => 'radio', 'options' => $priorites)) ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpPdfPriority', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
		<?php echo $this->BcForm->error('PdfConfig.priority') ?>
		<div id="helpPdfPriority" class="helptext">PDFファイルとHTMLの両方が設定されているときに、どちらを優先させて表示するかを設定します。</div>
	</td>
</tr>

