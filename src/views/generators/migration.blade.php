<?php echo '<?php'."\n"; ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThor{{ucfirst($plural)}}Table extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('{{$plural}}', function(Blueprint $table) {
                    $table->increments('id')->unsigned();
                    @foreach($generalFields as $name => $def)
@if($def->foreign_table!=false)
$table->integer('{{$name}}')->unsigned()->nullable()->default(null);
$table->foreign('{{$name}}')->references('id')->on('{{$def->foreign_table}}');
@else
$table->{{$def->data_type}}('{{$name}}')->nullable()->default(null);
@endif
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
                    @foreach($translatableFields as $name => $def)
@if($def->foreign_table!=false)
$table->integer('{{$name}}')->unsigned()->nullable()->default(null);
$table->foreign('{{$name}}')->references('id')->on('{{$def->foreign_table}}');
@else
$table->{{$def->data_type}}('{{$name}}')->nullable()->default(null);
@endif
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
            Schema::table('{{$singular}}', function(Blueprint $table) {
@foreach($generalFields as $name => $def)
@if($def->foreign_table!=false)
$table->dropForeign('{{$singular}}_{{$name}}_foreign');
@endif
                });
                @if($isTranslatable)
                
        Schema::table('{{$singular}}_texts', function(Blueprint $table) {
                    $table->dropForeign('{{$singular}}_texts_{{$singular}}_id_foreign');
                    $table->dropForeign('{{$singular}}_texts_language_id_foreign');
@foreach($translatableFields as $name => $def)
@if($def->foreign_table!=false)
$table->dropForeign('{{$singular}}_texts_{{$name}}_foreign');
@endif
                });
        Schema::drop('{{$singular}}_texts');
                @endif
        Schema::drop('{{$plural}}');
    }

}
