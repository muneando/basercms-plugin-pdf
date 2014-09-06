# Pdfリンク プラグイン

## 概要
Pdfリンクプラグインは、ブログ記事の本文の代わりににPDFファイルを表示できる[baserCMS](http://basercms.net)専用のプラグインです。

本プラグインはarata氏（[http://www.materializing.net/](http://www.materializing.net/)）の[Keywordプラグイン](https://github.com/materializing/keyword)を元に作成しました。

## インストール方法

1. 圧縮ファイルを解凍後、BASERCMS/app/plugins/Pdf に配置します。
2. 管理システムのプラグイン管理に入って、表示されている Pdfリンクプラグイン を有効化して下さい。
3. プラグインの有効化後、固定ページ編集画面とブログ記事編集画面にアクセスすると、入力項目にPDFファイル設定欄が追加されます。

## 設定方法

ブログプラグインの「プログ設定編集」で、ブログごとにPDFファイルの設定ができます。

| 設定項目 | 機能 |
|:---------|:-----|
| PDFファイル公開 | プログ記事からのPDFファイルリンクを有効・無効を設定します。|
| PDFファイル優先 | ブログ記事本文（HTML）とPDFファイルの両方を指定されていた場合に、どちらを優先して表示するかを指定します。|

各ブログ記事の「記事編集」で、PDFファイルをリンクさせます。編集アイコンからPDFファイル設定ページに行って、PDFファイルを選択してください。

PDFファイルは、Webブラウザからアクセスできないサーバーの/app/Upload/pdf/ディレクトリにアップロードします。アップロードできるPDFファイサイズは、サーバーの設定によります。memory_limit, post_max_size, upload_max_filesize の値を調整して下さい。

## 表示方法

### PDFファイルの表示

指定されたPDFファイルの表示をします。

```
{baserCMSのURL}/pdf/pdf/show/{コンテンツID}/{ブログ記事ID}/
```

PDFファイルが設定されていない場合は、ブログ記事の表示にリダイレクトされます。

### ヘルパーの利用

指定されたブログのコンテンツIDで指定して、PDFファイルにリンクしたタイトル一覧を表示します。

```
$this->BcBaser->pdfTilteList($contentsName, [$num])

・ string $contents プログコンテンツ名をアルファベットで指定します。

・ int $num 記事件数　初期値：5

例）

<?php $this->BcBaser->pdfTilteList('news') ?>
```

## 謝辞

- [http://basercms.net](http://basercms.net)
- [http://doc.basercms.net/](http://doc.basercms.net/)
- [http://cakephp.jp/](http://cakephp.jp/)
- [http://www.materializing.net/](http://www.materializing.net/)

