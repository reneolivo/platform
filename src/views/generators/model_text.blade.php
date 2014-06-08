<?php echo '<?php'; ?>

namespace {{$modelNamespace}};

use Thor\Models;

/**
 * {{ucfirst($singular)}} text model 
@foreach($generalFields as $name => $f)
 * @property {{$f->blueprint_function}} ${{$f->name}}
 
@endforeach
@foreach($translatableFields as $name => $f)
 * @property {{$f->blueprint_function}} ${{$f->name}}
 
@endforeach
 * @property integer $language_id
 * @property integer ${{$singular}}_id
 * @property timestamp $created_at
 * @property timestamp $updated_at 
 */
class {{$modelShortName}}Text extends Models\BaseText {
    
    protected $table = '{{$singular}}_texts';
    
    protected $fillable = array(
@foreach($translatableFields as $name => $f)
  '{{$f->name}}',
 
@endforeach
  'language_id',
  '{{$singular}}_id',
    );
    
    public static $rules = array();

    /**
     *
     * @return {{$modelShortName}} 
     */
    public function {{$singular}}() {
        return $this->master();
    }
}
