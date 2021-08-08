<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->index('updated_at', 'update_idx');
            $table->index('created_at', 'create_idx');
            $table->tinyInteger('status')->index('status_idx')->default(0); // default값을 준 이유 : todo를 생성했을때에는 아직 완료하지 않은 상태이기 때문에 0으로 설정
            $table->string('title')->index('title_idx');
            $table->string('reference')->default('');
            $table->engine = 'InnoDB'; // InnoDB는 MariaDB의 기본엔진. 설정안해도 관계 없음
            $table->charset = 'utf8mb4'; // charset을 uft8mb4로 사용하는 이유 : 채팅에서 혹시 모를 emoji 사용에 대비하여 적용
            // title, status, ucreated_at, created_at에 index를 준 이유 : 검색속도 향상을 위함. 만약 데이터량이 적을 경우는 index 제외해도 상관없음.
            // reference를 제일 밑으로 내린 이유 : index 중요도 위주로 PK 밑으로 순차적으로 생성 (중요도순으로 Primary Key->updateed_at->created_at->status->title 컬럼생성)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
