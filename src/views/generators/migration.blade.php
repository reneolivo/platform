<?php echo '<?php'."\n"; ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create{{ucfirst($plural)}}Table extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('{{$plural}}', function(Blueprint $table) {
                    $table->increments('id')->unsigned();
                    @foreach($fields as $i => $f)
$table->{{$f[0]}}('{{$f[1]}}')->nullable()->default(null);
                    @endforeach
$table->timestamps();
                });
                @if($isTranslatable)
                
        Schema::create('{{$singular}}_texts', function(Blueprint $table) {
                    $table->increments('id')->unsigned();
                    $table->integer('{{$singular}}_id')->unsigned();
                    $table->integer('language_id')->unsigned();
                    $table->foreign('{{$singular}}_id')->references('id')->on('{{$plural}}')->onDelete('cascade');
                    $table->foreign('language_id')->references('language_id')->on('languages')->onDelete('cascade');
                    @foreach($transFields as $i => $f)
$table->{{$f[0]}}('{{$f[1]}}')->nullable()->default(null);
                    @endforeach
                    $table->timestamps();
                });
                @endif
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
                @if($isTranslatable)
                
        Schema::table('{{$singular}}_texts', function(Blueprint $table) {
                    $table->dropForeign('{{$singular}}_texts_{{$singular}}_id_foreign');
                    $table->dropForeign('{{$singular}}_texts_language_id_foreign');
                });
        Schema::drop('{{$singular}}_texts');
                @endif
        Schema::drop('{{$plural}}');
    }

}
