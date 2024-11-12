<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Product;

class ProductSearchTest extends TestCase
{
    use DatabaseTransactions;

    public function testProductSearchDisplaysMatchingProducts()
    {
        $perPage = 8;
        $keyword = 'HDD'; // データベースに確実に存在するキーワードを設定

        // キーワードで検索した結果、該当する商品の総数を取得
        $totalMatchingProducts = Product::where('product_name', 'LIKE', "%{$keyword}%")->count();

        // 必要なページ数を計算
        $totalPages = ceil($totalMatchingProducts / $perPage);

        // 各ページを順にテスト
        for ($page = 1; $page <= $totalPages; $page++) {
            $response = $this->get("/?keyword={$keyword}&page={$page}");
            $response->assertStatus(200);

            // 現在のページに表示される商品を取得
            $products = Product::where('product_name', 'LIKE', "%{$keyword}%")
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            // 各商品がページに表示されているか確認
            foreach ($products as $product) {
                $response->assertSee($product->product_name); // 商品名が表示されていることを確認
                $response->assertSee($product->product_image); // 商品画像が表示されていることを確認
            }

            // ページネーションの"次へ"ボタンがあるかを確認
            if ($page < $totalPages) {
                $response->assertSee('rel="next"');
            } else {
                // 最終ページには"次へ"ボタンがないことを確認
                $response->assertDontSee('rel="next"');
            }
        }
    }

    public function testSearchResultsAreRetainedWhenNavigatingToMyListTab()
    {
        $perPage = 8;
        $keyword = 'HDD'; // データベースに確実に存在するキーワードを設定

        // 検索結果を表示するために / にアクセスして検索を実行
        $response = $this->get("/?keyword={$keyword}");
        $response->assertStatus(200);

        // 最初のページに表示される検索結果の商品を取得
        $products = Product::where('product_name', 'LIKE', "%{$keyword}%")
            ->take($perPage)
            ->get();

        // 検索結果の商品が表示されていることを確認
        foreach ($products as $product) {
            $response->assertSee($product->product_name);
            $response->assertSee($product->product_image);
        }

        // `/?tab=mylist` に移動
        $response = $this->get("/?tab=mylist&keyword={$keyword}");
        $response->assertStatus(200);

        // `/?tab=mylist` ページでも同じ検索結果が表示されていることを確認
        foreach ($products as $product) {
            $response->assertSee($product->product_name);
            $response->assertSee($product->product_image);
        }
    }
}
