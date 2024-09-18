<?php
/**
* Adapter class for Database Table Column
* On this each column will be defined as object of this class
*
* @version 1.0.0
*
* @package Core\Seme
* @since 1.0.0
*/
class Seme_Column
{
    public $required;
    public $allow_null;
    public $min_length;
    public $max_length;
    public $enum;
    public $value;
    public $default;

    public function __construct($required=0, $allow_null=true, $min_length=-1, $max_length=0, $enum=false)
    {
        $this->required = $required;
        $this->allow_null = $allow_null;
        $this->min_length = $min_length;
        $this->max_length = $max_length;
        $this->enum = $enum;
        $this->value = '';
        $this->default = 'null';
    }

    /**
     * Detect if current column is an enum
     *
     * @return boolean True if is enum, otherwise false
     */
    public function is_enum()
    {
        if ($this->enum != false || !empty($this->enum)) {
            return true;
        }
        return false;
    }

    /**
     * Validate the defined value is an enum
     *
     * @return boolean True if is enum, otherwise false
     */
    public function validate_enum($value)
    {
        if (!$this->is_enum()) {
            return true;
        }
        $result = false;
        foreach ($this->enum as $en) {
            if ($en == $value) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * Get defined enum for current column
     *
     * @return array list of enum value for current column
     */
    public function enum()
    {
        return $this->enum;
    }
}
