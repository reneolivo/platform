<?php echo '<?php'; ?>

namespace {{$model['classNamespace']}};

use Thor\Models;

/**
 * {{ucfirst($singular)}} text model 
@foreach($fields as $i => $f)
 * @property {{$f[0]}} ${{$f[1]}}
 
@endforeach
@foreach($transFields as $i => $f)
 * @property {{$f[0]}} ${{$f[1]}}
 
@endforeach
 * @property timestamp $created_at
 * @property timestamp $updated_at 
 */
class {{$model['className']}}Text extends Models\BaseText {
    
    protected $table = '{{$singular}}_texts';
    
    protected $guarded = array(
        
    );
    
    public static $rules = array();

    /**
     *
     * @return {{$model['className']}} 
     */
    public function {{$singular}}() {
        return $this->master();
    }
}
