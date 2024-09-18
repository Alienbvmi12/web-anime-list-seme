<?php
/**
* Wrapper Class for generating html label on Bootstrap format
*
* @version 1.0.0
*
* @package Core\Seme
* @since 1.0.0
*/
class Seme_Label {

    /** @var string */
    public $class;

    /** @var string */
    public $text;

    public function __construct($text='', $type='')
    {
        $this->class = "label label-$type";
        $this->text = $text;
    }

    /**
     * Get string of html bootstrap label
     *
     * @return string  Wrapped bootstrap html label, otherwise empty
     */
    public function html()
    {
        if (strlen($this->text)) {
            return '<label class="'.$this->class.'">'.$this->text.'</label>';
        }
        return '';
    }
}

/**
* Supporter Base Class for generating bootstrap label for flag
*
* @version 1.0.0
*
* @package Core\Seme
* @since 1.0.0
*/
class Seme_Flaglabel {

    /** @var array */
    public $labels;

    public function __construct()
    {
        $this->labels = array();
    }

    /**
     * Procedure for generate  is_deleted label flag
     *
     * @return object     Current object of this class
     */
    public function init_is_active()
    {
        $this->labels[0] = new Seme_Label('Tidak Aktif', 'default');
        $this->labels[1] = new Seme_Label('Aktif', 'info');

        return $this;
    }

    /**
     * Procedure for generate  is_deleted label flag
     *
     * @return object     Current object of this class
     */
    public function init_is_deleted()
    {
        $this->labels[0] = new Seme_Label('', '');
        $this->labels[1] = new Seme_Label('Dihapus', 'default');

        return $this;
    }

    /**
     * Get html bootstrap label for a key value
     *
     * @param  string $value  A value that defined as key of array labels
     *
     * @return string         Bootstrap label string
     */
    public function html($value)
    {
        return $this->labels[$value]->html();
    }
}
