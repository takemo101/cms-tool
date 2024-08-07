## 概要

このテーマは、microCMS でブログサイトを作成するためのテーマです。  
`blog`プリセットを利用することを前提に作成しています。

## `theme.json`

`theme.json`は、管理画面で表示するテーマのメタデータを json 形式で記述するファイルです。

```jsonc
{
    // uid テーマの製品ID：必須
    "uid": "dfb7b9a5-09d4-79ce-1e13-2bda4aad6fe7",
    // name テーマ名：必須
    "name": "Simply",
    // version テーマバージョン：必須
    "version": "1.0.0",
    // images テーマのスライド画像配列 assetsディレクトリにある画像ファイルの相対パスを指定する：任意
    // 先頭の画像ファイルがサムネイルとなる
    "images": ["image01.jpg", "image02.jpg"],
    // tags テーマのタグ配列：任意
    "tags": ["ブログ", "シンプル", "ミニマル"],
    // link テーマの詳細説明が記載されているサイトのリンク：任意
    "link": "https://github.com/takemo101/cms-tool",
    // preset テーマで利用するプリセットラベル：任意
    "preset": "microcms:blog",
    // author テーマ作者情報：必須
    "author": {
        // name テーマ作者名：必須
        "name": "Takemo101",
        // link テーマ作者のサイトリンク：任意
        "link": "https://github.com/takemo101"
    },
    // readonly テーマの拡張設定：任意
    "readonly": true,
    // extension テーマの拡張設定 設定内容はテーマごとに異なる：任意
    // テーマの動作を変更する場合にこの設定を編集する
    // 設定によってはテーマの動作に影響が出るため、変更する場合は注意する
    "extension": {
        // endpoints 各データ種別ごとのmicroCMSのApiエンドポイント：任意
        "endpoints": {
            // 記事Api
            "blog": "blogs",
            // カテゴリApi
            "category": "categories",
            // タグApi
            "tag": "tags"
        },
        // signatures 各ページ種別ごとのURL：任意
        "signatures": {
            // 記事一覧ページ
            "blog": "blog",
            // カテゴリごとの記事一覧ページ
            "category": "category",
            // タグごとの記事一覧ページ
            "tag": "tag"
        },
        // fields 記事データmicroCMSのApiの関連データのフィールド名：任意
        "fields": {
            // 記事のカテゴリフィールド名
            "category": "category",
            // 記事のタグフィールド名
            "tag": "tags"
        }
    },
    "schema": {
        // テーマをカスタマイズするための入力スキーマ定義を記述
    }
}
```

> `extension`の設定は、テーマやプリセットごとに異なるため、変更する場合は注意してください。

## トップページのテクノロジー記事一覧表示

トップページのテクノロジー記事一覧（新着記事の下のカラムに表示される記事一覧）は、テーマのカスタマイズページから表示したいカテゴリの ID を設定することで表示できます。

## 画面プレビュー

microCMS の投稿した記事の画面プレビューを利用する場合は、microCMS の画面プレビュー設定の遷移先 URL に以下を設定してください。

```
https://ホスト名/blog/{CONTENT_ID}?key={DRAFT_KEY}

# ホスト名が localhost:8080 の場合 http://localhost:8080/blog/{CONTENT_ID}?key={DRAFT_KEY} のようになります
```

[操作マニュアルはこちら](https://document.microcms.io/manual/screen-preview)

> `theme.json`の`extension.signatures.blog`の値を変更した場合は、`/blog`の部分も変更されますので、ご注意ください。
