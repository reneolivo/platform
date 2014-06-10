<?php echo '<?php'; ?>

namespace {{$modelNamespace}};

use Thor\Models\Behaviours;

/**
 * {{ucfirst($singular)}} model 
@foreach($generalFields as $name => $f)
 * @property {{$f->blueprint_function}} ${{$f->name}}
 
@endforeach
@if($isTranslatable)
@foreach($translatableFields as $name => $f)
 * @property {{$f->blueprint_function}} ${{$f->name}}
 
@endforeach
 * @property integer $language_id
 * @property integer ${{$singular}}_id
 @endif
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class {{$modelShortName}} extends \Thor\Models\Base {{$modelImplements}} {
    {{$modelUses}}
    
    protected $table = '{{$plural}}';
    
    protected $fillable = array(
@foreach($generalFields as $name => $f)
  '{{$f->name}}',
 
@endforeach
    );
    
    public static $rules = array();
}
