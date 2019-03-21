<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    // SoftDeletesトレイトを使う
    use SoftDeletes;
    
    // 対象テーブルのプライマリキーのカラム名を指定する。デフォルトは'idというカラム名が想定されている。
    protected $primaryKey = 'article_id';
    
    // 「複数代入」を利用するときに指定する。追加・編集可能なカラム名のみを指定する。
    // $guardedプロパティを利用すると、逆に、追加・編集不可能なカラムを指定できる。
    protected $fillable = ['post_date', 'title', 'body'];
    
    // $datesプロパティには、日時が入るカラムを設定する（日付ミューテタ）
    // そうすると、その値が自動的にCarbonインスタンスに変換される
    protected $dates = ['post_date', 'created_at', 'update_at', 'delete_at'];
    
    
    /**
     * post_dateのアクセサYYYY/MM/DDのフォーマットにする
     * 
     * @return string
     */
     public function getPostDateTextAttribute()
     {
         // アクセスを定義しておくと$article->post_date_textという
         // プロパティにアクセスしたときに、このメソッドの返り値が返る
         // 'post_date'は$datesプロパティに設定してあるので、自動的にCarbonインスタンスとなる
         return $this->post_date->format('Y/m/d');
     }
     
     /**
      * post_dateのミューテタYYYY-MM-DDのフォーマットでセットする
      * 
      * @param $value
      */
      public function setPostDateAttribute($value)
      {
          // ミューテタはプロパティに設定しようとする値を受け取って加工する
          // そして加工したものをEloquentモデルの$attributesプロパティに設定する
          // 例えば$article->post_date = '2018/07/07'とすると、
          // このメソッドが自動的に呼び出され、引数$valueには'2018/07/07'が渡される
          // 今回はDBに入れることのできるYYYY-MM-DDのフォーマットにする
          $post_date = new Carbon($value);
          $this->attributes['post_date'] = $post_date->format('Y-m-d');
      }
}
