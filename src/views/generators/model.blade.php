<?php echo '<?php'; ?>

namespace {{$modelNamespace}};

use Thor\Models;

/**
 * {{ucfirst($singular)}} model 
@foreach($generalFields as $name => $f)
 * @property {{$f->data_type}} ${{$f->name}}
 
@endforeach
@foreach($translatableFields as $name => $f)
 * @property {{$f->data_type}} ${{$f->name}}
 
@endforeach
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class {{$modelShortName}} extends Models\Base {{$modelImplements}} {
    {{$modelUses}}
    
    protected $table = '{{$plural}}';
    
    protected $fillable = array(
@foreach($generalFields as $name => $f)
  '${{$f->name}}',
 
@endforeach
    );
    
    public static $rules = array();
}
