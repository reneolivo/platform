<?php echo '<?php'; ?>

namespace {{$model['classNamespace']}};

use Thor\Models;

/**
 * {{ucfirst($singular)}} model 
@foreach($fields as $i => $f)
 * @property {{$f[0]}} ${{$f[1]}}
 
@endforeach
@foreach($transFields as $i => $f)
 * @property {{$f[0]}} ${{$f[1]}}
 
@endforeach
 * @property timestamp $created_at
 * @property timestamp $updated_at
 */
class {{$model['className']}} extends Models\Base {{$model['implements']}} {
    {{$model['uses']}}
    
    protected $table = '{{$plural}}';
    
    protected $guarded = array(
        
    );
    
    public static $rules = array(
        
    );
}
