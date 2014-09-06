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
						$("#pdf-file-name").html("PDFファイルは指定されていません。");
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

<?php if($this->action == "admin_edit"): ?>
<tr>
	<th class="col-head"><?php echo $this->BcForm->label('Pdf.pdf_file', 'PDFファイル') ?></th>
	<td class="col-input">
	<?php
		$this->BcBaser->link (
				$this->BcBaser->getImg(
						'admin/icn_tool_check.png',
						array (
								'width' => 24,
								'height' => 24,
								'alt' => '確認',
								'class' => 'btn' 
								)
				),
				array (
						'plugin' => 'pdf',
						'controller' => 'pdf',
						'action' => 'show',
						$this->request->data ['BlogPost'] ['blog_content_id'],
						$this->request->data ['BlogPost'] ['id'] 
						),
				array (
						'title' => '確認',
						'target' => '_blank' 
						)
				);
		$this->BcBaser->link(
				$this->BcBaser->getImg(
						'admin/icn_tool_edit.png',
						array (
								'width' => 24,
								'height' => 24,
								'alt' => '編集',
								'class' => 'btn' 
								)
				),
				array (
						'plugin' => 'pdf',
						'controller' => 'pdf',
						'action' => 'edit',
						$this->request->data ['BlogPost'] ['blog_content_id'],
						$this->request->data ['BlogPost'] ['id'] 
						),
				array (
						'title' => '編集' 
						)
				);
		$this->BcBaser->link(
				$this->BcBaser->getImg(
						'admin/icn_tool_delete.png',
						array(
								'width' => 24,
								'height' => 24,
								'alt' => '削除',
								'class' => 'btn' 
								)
				),
				array(
						'plugin' => 'pdf',
						'controller' => 'pdf',
						'action' => 'ajax_delete',
						$this->request->data ['BlogPost'] ['id'] 
						),
				array(
						'title' => '削除',
						'class' => 'btn-delete' 
						)
				);
		if (empty($this->request->data ['Pdf'] ['pdf_file'])) {
			echo 'PDFファイルは指定されていません。';
		} else {
			echo '<span id="pdf-file-name">';
			$this->BcBaser->link (
					$this->request->data ['Pdf'] ['pdf_file'],
					array (
							'plugin' => 'pdf',
							'controller' => 'pdf',
							'action' => 'show',
							$this->request->data ['BlogPost'] ['blog_content_id'],
							$this->request->data ['BlogPost'] ['id'] 
							),
					array(
							'title' => '確認',
							'target' => '_blank',
							)
					);
			echo '</span>';
		}
	?>
	</td>
</tr>
<?php else: ?>
<?php // 利用設定が無効の場合 ?>
<?php echo $this->BcForm->input('Pdf.pdf_file', array('type' => 'hidden')) ?>
<?php endif ?>
