<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\AdminBlogRequest;
use App\Models\Article;

class AdminBlogController extends Controller
{
    /** @var Article */
    protected $article;
    
    function __construct(Article $article)
    {
        // Articleモデルクラスのインスタンスを作成
        // 「依存注入」により、コンストラクタの引数にタイプヒントを指定するだけで、
        // インスタンスが生成される（コンストラクタ-インジェクション)
        $this->article = $article;
    }
    
    /**
     * ブログ記事入力フォーム
     * 
     * @param int $article_id 記事ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */ 
     public function form(int $article_id = null)
     {
         // メソッドの引数に指定すれば、ルートパラメータを取得できる
         
         // Eloquentモデルはクエリビルだとしても動作するのでfindメソッドで記事データを取得
         // 返り値はnullかApp\Models\Article Object
         $article = $this->article->find($article_id);
         
         // 記事データがあればtoArrayメソッドで配列にしておき、フォーマットしたpost_dateを入れる
         $input = [];
         if ($article) {
             $input = $article->toArray();
             $input['post_date'] = $article->post_date_text;
         } else {
             $article_id = null;
         }
         
         // oldヘルパーを使うと、直前のリクエストのフラッシュデータを取得できる
         // ここではバリデートエラーとなったときに、入力していた値をoldヘルパーで取得する
         // DBから取得した値よりも優先して表示するため、array_mergeの第二引数に設定する
         $input = array_merge($input, old());
         
         // Viewテンプレートへ値を渡すときは、第二引数に連想配列を設定する
         // Viewテンプレートでは連想配列のキー名で値を取り出せる
         // return view('admin_blog.form', ['input' => $input, 'article_id' => $article_id]);
         // compact 関数を使うと便利
         // resources/views配下にある、どのテンプレートを使うか指定。ディレクトリの階層はピリオドで表現できる
         // この例ではresources/views/admin_blog/form.blade.phpが読み込まれる
         return view('admin_blog.form', compact('input', 'article_id'));
     }
     
     /**
      * ブログ記事保存処理
      * 
      * @param AdminBlogRequest $request
      * @return \Illuminate\Http\RedirectResponse
      */
      public function post(AdminBlogRequest $request)
      {
          // こちらも引数にタイプヒントを指定すると、
          // AdminBlogRequestのインスタンスが生成される（メソッドインジェクション）
          // そして、AdminBlogRequestで設定したバリデートも実行される（フォームリクエストバリデーション）
          
          // 入力値の取得
          $input = $request->input();
          
          // array_getヘルパは配列から指定されたキーの値を取り出すメソッド
          // 指定したキーが存在しない場合のデフォルト値を第三引数に設定できる
          // 指定したキーが存在しなくても、エラーにならずデフォルト値が返るのが便利
          $article_id = array_get($input, 'article_id');
          
          // create メソッドで複数代入を実行する。
          // 対象テーブルのカラム名と配列のキー名が一致する場合、一致するカラムに一致するデータが入る
          // Eloquentモデルから利用できるupdateOrCreateメソッド、第一引数の値でDBを検索し
          // レコードが見つかったら第二引数の値でそのレコードを更新、見つからなかったら新規作成する
          // ここではarticle_idでレコードを検索し、第二引数の入力値でレコードを更新、または新規作成している
          $article = $this->article->updateOrCreate(compact('article_id'), $input);
          
          // リダイレクトでフォーム画面に戻る
          // routeヘルパーでリダイレクト先を指定。ルートのエイリアスを使う場合は、routeヘルパーを使う
          // withメソッドで、セッション二次のリクエスト限りのデータを保存する
          // フォーム画面にリダイレクト。その際、routeメソッドの第二引数にパラメータを指定できる
          return redirect()
            ->route('admin_form', ['article_id' => $article->article_id])
            ->with('message', '記事を保存しました');
      }
      
      /**
       * ブログ記事削除処理
       * 
       * @param AdminBlogRequest $request
       * @return \Illuminate\Http\RedirectResponse
       */
       public function delete(AdminBlogRequest $request)
       {
           // 記事IDの取得
           $article_id = $request->input('article_id');
           
           // Articleモデルを取得してdeleteメソッドを実行することで削除できる
           // このとき万が一$arrticleがnullになる場合も想定して実装するのが良い(今回は使わない)
           // $article = $this->article->find($article_id);
           // $article->delete();
           
           // 主キーの値があるならdestroyメソッドで削除することができる
           // 引数は配列でも可。返り値は削除したレコード数
           $result = $this->article->destroy($article_id);
           $message = ($result) ? '記事を削除しました':'記事の削除に失敗しました';
           
           // フォーム画面へリダイレクト
           return redirect()->route('admin_form')->with('message', $message);
       }

}
