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
	<?php echo $this->BcForm->create('PdfConfig', array('url' => array('action' => 'edit'))) ?>
	<?php echo $this->BcForm->input('PdfConfig.id', array('type' => 'hidden')) ?>
	<?php echo $this->BcForm->input('PdfConfig.content_id', array('type' => 'hidden')) ?>
	<?php echo $this->BcForm->input('PdfConfig.model', array('type' => 'hidden')) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('PdfConfig', array('url' => array('action' => 'add'))) ?>
<?php endif ?>

<h2><?php echo $blogContentDatas[$this->request->data['PdfConfig']['content_id']] ?></h2>

<div id="PdfConfigConfigTable">
<table cellpadding="0" cellspacing="0" class="form-table section">
	<tr>
		<th class="col-head"><?php echo $this->BcForm->label('PdfConfig.id', 'NO') ?></th>
		<td class="col-input">
			<?php echo $this->BcForm->value('PdfConfig.id') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">
			<?php echo $this->BcForm->label('PdfConfig.status', 'PDFリンクの利用') ?>
			<div id="helptextPdfConfigStatus" class="helptext">
				<ul>
					<li>PDFリンク利用の有無を指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input">
			<?php echo $this->BcForm->input('PdfConfig.status', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('利用'))) ?>
			<?php echo $this->BcForm->error('PdfConfig.status') ?>
		</td>
	</tr>
</table>
</div>

<div class="submit">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'btn-red button')) ?>
</div>
<?php echo $this->BcForm->end() ?>
