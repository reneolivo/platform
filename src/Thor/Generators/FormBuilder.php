<?php

namespace Thor\Generators;

use HTML;

/**
 * Twitter Bootstrap 3 Form builder
 */
class FormBuilder extends \Illuminate\Html\FormBuilder
{

    public function __construct(HtmlBuilder $html, \Illuminate\Routing\UrlGenerator $url, $csrfToken)
    {
        parent::__construct($html, $url, $csrfToken);
    }

    public function bsFields(array $fields)
    {
        $html = '';
        foreach ($fields as $field) {
            $html.=call_user_func_array('\\Form::bsField', $field);
        }
        return $html;
    }

    public function bsField($labelText, $name, $options = array(), $type = 'text', $value = null, $containerAttrs = array())
    {
        if ($type == 'datepicker') {
            $type = 'text';
            $options = array_merge(array('class' => 'form-control widget-datepicker'), $options);
        }
        if ($type == 'colorpicker') {
            $type = 'text';
            $options = array_merge(array('class' => 'form-control widget-colorpicker'), $options);
        }
        if (in_array($type, array('select', 'select2', 'combobox'))) {
            $options = array_merge(array('class'=>'form-control'), $options);
            if(in_array($type, array('select2', 'combobox'))){
                $options['class'].=' widget-select2';
            }
            $selected = null;
            if(isset($options['selected'])){
                $selected = $options['selected'];
                unset($options['selected']);
            }
            return $this->bsFormgroup($labelText, $this->select($name, $value
                    , $selected, $options), $containerAttrs);
        }
        if ($type == 'checkbox') {
            return $this->bsFormgroup(false, $this->bsCheckbox($labelText, $name, ($value === null) ? 1 : $value, $options, true), $containerAttrs);
        }
        if ($type == 'radio') {
            return $this->bsFormgroup(false, $this->bsRadio($labelText, $name, ($value === null) ? 1 : $value, $options, true), $containerAttrs);
        }
        return $this->bsFormgroup($labelText, ($type == 'textarea') ? $this->bsTextarea($name, $value, $options) :
                                $this->bsInput($type, $name, $value, $options), $containerAttrs);
    }

    public function bsFormgroup($labelText, $content, $containerAttrs = array())
    {
        return HTML::div(array_merge(array('class' => 'form-group'), $containerAttrs), (($labelText !== false) ? (HTML::label(array(), $labelText) . "\n") : "") . $content);
    }

    public function bsCheckbox($text, $name, $value = 1, $options = array(), $inline = false, $containerAttrs = array())
    {
        $checked = (isset($options['checked']) and ( $options['checked'] === true)) or ( in_array('checked', $options));
        if ($inline) {
            return HTML::label(array_merge(array('class' => 'checkbox-inline'), $containerAttrs), $this->checkbox($name, $value, $checked, $options) . ' ' . $text);
        } else {
            return HTML::div(array_merge(array('class' => 'checkbox'), $containerAttrs), HTML::label(array(), $this->checkbox($name, $value, $checked, $options) . ' ' . $text));
        }
    }

    public function bsRadio($text, $name, $value = null, $options = array(), $inline = false, $containerAttrs = array())
    {
        $checked = (isset($options['checked']) and ( $options['checked'] === true)) or ( in_array('checked', $options));
        if ($inline) {
            return HTML::label(array_merge(array('class' => 'radio-inline'), $containerAttrs), $this->radio($name, $value, $checked, $options) . ' ' . $text);
        } else {
            return HTML::div(array_merge(array('class' => 'radio'), $containerAttrs), HTML::label(array(), $this->radio($name, $value, $checked, $options) . ' ' . $text));
        }
    }

    public function bsInputgroup($attrs = array(), $before = '', $after = '', $groupAttrs = array())
    {
        $html = '';
        if (!empty($before)) {
            if (preg_match('/btn-/', $before)) {
                $html .= '<span class="input-group-btn">' . $before . '</span>';
            } else {
                $html .= '<span class="input-group-addon">' . $before . '</span>';
            }
        }
        $attrs = array_merge(array('type' => 'text', 'class' => 'form-control', 'name' => null, 'value' => null), $attrs);

        $html .= $this->input($attrs['type'], $attrs['name'], $attrs['value'], $attrs);

        if (!empty($after)) {
            if (preg_match('/btn-/', $after)) {
                $html .= '<span class="input-group-btn">' . $after . '</span>';
            } else {
                $html .= '<span class="input-group-addon">' . $after . '</span>';
            }
        }
        return HTML::div(array_merge(array('class' => 'input-group'), $groupAttrs), $html);
    }

    public function bsTextarea($name, $value = null, $options = array())
    {
        return $this->textarea($name, $value, array_merge(array('class' => 'form-control'), $options));
    }

    public function bsInput($type, $name, $value = null, $options = array())
    {
        return $this->input($type, $name, $value, array_merge(array('class' => 'form-control'), $options));
    }

    public function bsText($name, $value = null, $options = array())
    {
        return $this->bsInput('text', $name, $value, $options);
    }

    public function bsNumber($name, $value = null, $options = array())
    {
        return $this->bsInput('number', $name, $value, $options);
    }

    public function bsEmail($name, $value = null, $options = array())
    {
        return $this->bsInput('email', $name, $value, $options);
    }

    public function bsUrl($name, $value = null, $options = array())
    {
        return $this->bsInput('url', $name, $value, $options);
    }

    public function bsTel($name, $value = null, $options = array())
    {
        return $this->bsInput('tel', $name, $value, $options);
    }

    public function bsColor($name, $value = null, $options = array())
    {
        return $this->bsInput('color', $name, $value, $options);
    }

    public function bsDate($name, $value = null, $options = array())
    {
        return $this->bsInput('date', $name, $value, $options);
    }

    public function bsDatetime($name, $value = null, $options = array())
    {
        return $this->bsInput('datetime', $name, $value, $options);
    }

    public function bsPassword($name, $value = null, $options = array())
    {
        return $this->bsInput('password', $name, $value, $options);
    }

    public function bsFile($name, $value = null, $options = array())
    {
        return $this->bsInput('file', $name, $value, $options);
    }

    public function bsRange($name, $value = null, $options = array())
    {
        return $this->bsInput('range', $name, $value, $options);
    }

}
