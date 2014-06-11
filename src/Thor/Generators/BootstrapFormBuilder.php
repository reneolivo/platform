<?php

namespace Thor\Generators;

use HTML;

/**
 * Twitter Bootstrap 3 Form builder
 */
class BootstrapFormBuilder extends \Illuminate\Html\FormBuilder
{

    public function button($value = null, $options = array())
    {
        return parent::button($value, array_merge(['class' => 'btn btn-default'], $options));
    }

    public function checkbox($name, $value = 1, $checked = null, $options = array(), $label = false)
    {
        if (empty($label)) {
            $label = $name;
        }
        return parent::label(null, parent::checkbox($name, $value, $checked, $options)
                        , array('class' => 'checkbox-inline'));
    }

    public function radio($name, $value = null, $checked = null, $options = array(), $label = false)
    {
        if (empty($label)) {
            $label = $name;
        }
        return parent::label(null, parent::radio($name, $value, $checked, $options, $label = false)
                        , array('class' => 'radio-inline'));
    }

    public function input($type, $name, $value = null, $options = array(), $label = false)
    {
        if (in_array($type, array('button', 'reset', 'submit'))) {
            return parent::input($type, $name, $value, array_merge(['class' => 'btn btn-default'], $options));
        } elseif (in_array($type, array('hidden', 'checkbox', 'radio'))) {
            return parent::input($type, $name, $value, $options);
        } else {
            return $this->formgroup(parent::input($type, $name, $value
                    , array_merge(['class' => 'form-control'], $options)), $label);
        }
    }
    
    public function textarea($name, $value = null, $options = array(), $label = false)
    {
        parent::textarea($name, $value, $options);
    }
    
    public function select($name, $list = array(), $selected = null, $options = array(), $label = false)
    {
        parent::select($name, $list, $selected, $options, $label);
    }

    public function formgroup($content, $label = false, $options = array())
    {
        $label = (($label !== false) ? (HTML::label(array(), $label) . "\n") : "");
        return HTML::div(array_value_prefix($options, 'form-group ', 'class'), $label . $content);
    }

    public function colorpicker($name, $value = null, $options = array(), $label = false)
    {
        return $this->input('text', $name, $value, $options, $label, $label);
    }

    public function datepicker($name, $value = null, $options = array(), $label = false)
    {
        return $this->input('text', $name, $value, $options, $label, $label);
    }

    public function select2($name, $list = array(), $selected = array(), $options = array(), $label = false)
    {
        return $this->select($name, $list, $selected, $options, $label);
    }

    public function wysihtml5($name, $value = null, $options = array(), $label = false)
    {
        return $this->textarea($name, $value, $options, $label);
    }

}
