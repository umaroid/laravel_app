<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create() の第一引数がテーブル名、第二引数はクロージャーで、中に作成するカラム情報を書いていく
        Schema::create('articles', function (Blueprint $table) {
            
            $table->bigIncrements('article_id'); // auto increment の unsigned INT カラム
            $table->date('post_date'); // DATEカラム
            $table->string('title'); // VARCHARカラム
            $table->text('body'); // TEXTカラム
            $table->softDeletes(); // ソフトデリート（論理削除）用のdelete_atカラム（TIMESTAMP型）
            $table->timestamps(); // created_atとupdate_at(TIMESTAMP型)カラムの両方を作成
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
