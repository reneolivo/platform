<?php

namespace Thor\Generators;

use Config,
    Str;

class ResourceResolver extends \ArrayObject
{

    public $singular;
    public $plural;
    public $generalFields = array();
    public $translatableFields = array();
    public $listableFields = array();
    public $behaviours = array();
    public $viewParent;
    public $viewSection;
    public $viewBasepath;
    public $controllerPrefix;
    public $controllerSuffix;
    public $modelPrefix;
    public $modelSuffix;
    //
    public $modelFullName;
    public $modelShortName;
    public $modelNamespace;
    public $modelPath;
    public $modelFile;
    public $modelImplements;
    public $modelUses;
    public $controllerFullName;
    public $controllerShortName;
    public $controllerNamespace;
    public $controllerPath;
    public $controllerFile;
    public $migrationFile;
    //
    public $isTranslatable;
    public $resolver;
    //
    public $defaultPageableFields = array(
        'taxonomy' => array('string', 'taxonomy', 'text'),
        'controller' => array('string', 'controller', 'text'),
        'action' => array('string', 'action', 'text'),
        'view' => array('string', 'view', 'text'),
        'is_https' => array('boolean', 'is_https', 'checkbox'),
        'is_indexable' => array('boolean', 'is_indexable', 'checkbox'),
        'is_deletable' => array('boolean', 'is_deletable', 'checkbox'),
        'sorting' => array('integer', 'sorting', 'number'),
        'status' => array('string', 'status', 'text'), // general pageable status
    );
    public $defaultPageableTranslatableFields = array(
        'title' => array('string', 'title', 'text'),
        'content' => array('text', 'content', 'html'),
        'slug' => array('text', 'slug', 'text'),
        'window_title' => array('string', 'window_title', 'text'),
        'meta_description' => array('string', 'meta_description', 'text'),
        'meta_keywords' => array('string', 'meta_keywords', 'text'),
        'canonical_url' => array('string', 'canonical_url', 'text'),
        'redirect_url' => array('string', 'redirect_url', 'text'),
        'redirect_code' => array('string', 'redirect_code', 'text'), // redirect status code
    );

    public function __construct($singular, $behaviours = false, $generalFields = false, $translatableFields = false, $listableFields = false)
    {
        $singular = strtolower(trim($singular));
        if (empty($singular)) {
            throw \InvalidArgumentException('Singular resource name cannot be empty');
        }
        $this->singular = Str::singular($singular);
        $this->plural = Str::plural($this->singular);

        if (!is_array($behaviours)) {
            $behaviours = $this->parseStrList($behaviours);
        }
        if (!is_array($generalFields)) {
            $generalFields = $this->parseFields($generalFields);
        }
        if (!is_array($translatableFields)) {
            $translatableFields = $this->parseFields($translatableFields);
        }
        if (!is_array($listableFields)) {
            $listableFields = $this->parseStrList($listableFields);
        }

        foreach ($listableFields as $k => $v) {
            
        }

        $this->behaviours = $behaviours;
        $this->generalFields = $generalFields;
        $this->translatableFields = $translatableFields;
        $this->listableFields = $listableFields;

        $this->fillDefaultConfig();
        $this->fillClassInfo('model');
        $this->fillClassInfo('controller');
        $this->migrationFile = app_path() . '/database/migrations/' . date('Y_m_d_His') . '_' . 'create_thor_' . $this->plural . '_table';

        $this->fillFields();
        $this->fillBehaviours();
        $this->fillListableFields();
        $this->isTranslatable = $this->is('translatable');
        $this->resolver = $this;
    }

    protected function fillListableFields()
    {

        if (empty($this->listableFields)) {
            $this->listableFields = array_merge(array('id'), 
                    array_keys($this->generalFields), array('updated_at'));
        }
        $fields = array();
        foreach($this->listableFields as $name){
            if(isset($this->generalFields[$fields])){
                $fields[$name] = $this->generalFields[$fields];
            }elseif(isset($this->translatableFields[$fields])){
                $fields[$name] = $this->translatableFields[$fields];
            }else{
                $fields[$name] = false;
            }
        }
        $this->listableFields = $fields;
    }

    protected function fillDefaultConfig()
    {

        $config = Config::get('thor::generators');
        $this->viewParent = $config['view_parent'];
        $this->viewSection = $config['view_section'];
        $this->viewBasepath = $config['view_basepath'];
        $this->controllerPrefix = $config['controller_prefix'];
        $this->controllerSuffix = $config['controller_suffix'];
        $this->modelPrefix = $config['model_prefix'];
        $this->modelSuffix = $config['model_suffix'];
    }

    protected function fillClassInfo($classType)
    {
        $this[$classType . 'FullName'] = $fullClass = ($this[$classType . 'Prefix'] . ucfirst(Str::camel($this->singular)) . $this[$classType . 'Suffix']);
        $parts = explode('/', str_replace('\\', '/', trim($fullClass, '/\\ ')));
        $this[$classType . 'ShortName'] = array_pop($parts);
        $this[$classType . 'Namespace'] = implode('\\', $parts);
        $this[$classType . 'Path'] = app_path() . '/src/' . trim(implode('/', $parts), '/\\ ');
        $this[$classType . 'File'] = $this[$classType . 'Path'] . '/' . $this[$classType . 'ShortName'] . '.php';
        if (file_exists($this[$classType . 'File'] . '.php')) {
            $this[$classType . 'File'] .= '_' . date('Y_m_d_His');
        }
    }

    protected function fillFields()
    {
        if ($this->has('pageable')) {
            $this->generalFields = array_merge($this->defaultPageableFields, $this->generalFields);
            $this->translatableFields = array_merge($this->defaultPageableTranslatableFields, $this->translatableFields);
        }

        if ((is_array($this->translatableFields) and ( count($this->translatableFields) > 0))) {
            $transFields = array_merge($transFields, array(
                'translation_status' => (object) array(
                    'name' => 'translation_status',
                    'label' => 'Translation Status',
                    'data_type' => 'string',
                    'control_type' => 'text',
                    'foreign_table' => false,
                    'foreign_column' => false,
                    'foreign_listwith' => false
                ),
            ));
        }
    }

    protected function fillBehaviours()
    {
        $isTranslatable = (count($this->translatableFields) > 0);
        if ($isTranslatable) {
            $this->behaviours[] = 'translatable';
        }
        $this->behaviours = array_unique($this->behaviours);

        $modelImplements = array();
        $modelUses = array();

        foreach ($this->behaviours as $name) {
            if (($name == 'translatable') and ( !$this->is('pageable'))) {
                $modelImplements[] = $this->modelPrefix . 'I' . ucfirst($name);
                $modelUses[] = $this->modelPrefix . 'T' . ucfirst($name);
            }
        }

        $this->modelImplements = empty($modelImplements) ? '' : ('implements ' . implode(', ', $modelImplements));
        $this->modelUses = empty($modelUses) ? '' : ('use ' . implode(', ', $modelUses) . ';');
    }

    public function has($fieldName)
    {
        return in_array($fieldName, array_keys($this->generalFields))
                or in_array($fieldName, array_keys($this->translatableFields));
    }

    public function is($behaviourName)
    {
        return in_array($behaviourName, $this->behaviours);
    }

    public function parseStrList($str)
    {
        $parsed = array();
        if (empty($str) or ! is_string($str)) {
            return array();
        }
        if (is_string($str)) {
            $parsed = explode(',', trim(strtolower($str), ',;|: '));
        }
        return $parsed;
    }

    public function parseFields($str)
    {
        $str = $this->parseStrList($str);
        if (empty($str)) {
            return array();
        }
        $parsed = array();

        $fields = explode(',', $str);
        foreach ($fields as $field) {
            $data = array(
                'name' => false,
                'label' => false,
                'data_type' => 'string',
                'control_type' => 'text',
                'foreign_table' => false,
                'foreign_listwith' => false
            );
            $params = explode(':', trim($field, ',;|: '));
            if (empty($params)) {
                return false;
            }
            $data['name'] = $params[0];
            $data['label'] = Str::title(str_replace('_', ' ', $params[0]));

            if (isset($params[1])) {
                $data['data_type'] = $params[1];
            }
            if (isset($params[2])) {
                $data['control_type'] = $params[2];
            }else{
                switch($data['data_type']){
                    case 'boolean': $data['control_type'] = 'checkbox'; break;
                    case 'integer': $data['control_type'] = 'number'; break;
                    case 'timestamp': $data['control_type'] = 'datepicker'; break;
                    default: $data['control_type'] = 'number'; break;
                }
            }
            if (isset($params[3])) {
                $data['foreign_table'] = $params[3];
            }
            if (isset($params[4])) {
                $data['foreign_column'] = $params[4];
            }
            if (isset($params[5])) {
                $data['foreign_listwith'] = $params[5];
            }
            $parsed[$data['name']] = (object) $data;
        }

        return $parsed;
    }

    public function export()
    {
        return get_object_public_vars($this);
    }

}
