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
<?php if($this->request->params['action'] != 'admin_add'): ?>
	<?php echo $this->BcForm->create('Pdf', array('type' => 'file', 'url' => array('controller' => 'pdf', 'action' => 'edit', $contentId, $blogPostDatas['BlogPost']['id']))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('Pdf', array('type' => 'file', 'url' => array('controller' => 'pdf', 'action' => 'add', $contentId, $blogPostDatas['BlogPost']['id']))) ?>
<?php endif ?>
	<?php echo $this->BcForm->input('Pdf.id', array('type' => 'hidden', 'value' => $blogPostDatas['BlogPost']['id'])) ?>
	<?php echo $this->BcForm->input('Pdf.content_id', array('type' => 'hidden', 'value' => $contentId)) ?>

<div id="PdfConfigTable">
<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('Pdf.pdf_file', 'PDFファイル') ?>
			<div id="helptextPdfConfigPdfFile" class="helptext">
				<ul>
					<li>PDFファイルを指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php
				if(isset($this->request->data['Pdf']['pdf_file'])) {
					$this->BcBaser->link (
							$this->request->data ['Pdf'] ['pdf_file'],
							array (
									'plugin' => 'pdf',
									'controller' => 'pdf',
									'action' => 'show',
									$contentId,
									$this->request->data ['Pdf'] ['id']
							),
							array(
									'title' => '確認',
									'target' => '_blank',
									'class' => 'pdf-file-name'
							)
					);
				} else {
					 echo 'PDFファイルは指定されていません。';
				} ?>
			<?php echo $this->BcForm->input('Pdf.pdf_file', array('type' => 'hidden')) ?><br>
			<?php echo $this->BcForm->input('Pdf.pdf_upload_file', array('type' => 'file')) ?>
			<?php echo $this->BcForm->error('Pdf.pdf_file') ?>
			</td>
	</tr>
</table>
</div>

<div class="submit">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'btn-red button')) ?>
</div>
<?php echo $this->BcForm->end() ?>
