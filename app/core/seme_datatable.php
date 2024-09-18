<?php
/**
* Generator Class for Datatable
*   Contains column selection method, sorting method and helper for API for ajax request
*
* @version 1.0.0
*
* @package Core\Seme
* @since 1.0.0
*/
class Seme_Datatable
{
    public $keyword;
    public $iSortCol_0;
    public $sSortDir_0;
    public $page;
    public $pagesize;

    /** Defined columns name for column selection purpose */
    public $columns;
    public $labels;
    public $search_placeholder;

    const DESCENDING = 'DESC';
    const ASCENDING = 'ASC';

    public function __construct($columns = array())
    {
        $this->iSortCol_0 = 0;
        $this->sSortDir_0 = self::DESCENDING;
        $this->page = 1;
        $this->pagesize = 10;
        $this->keyword = '';
        $this->columns = $columns;
        $this->search_placeholder = 'cari data berdasarkan kata kunci..';
    }

    private function post($key, $default_value='')
    {
        if (!isset($_POST[$key])) {
            return $default_value;
        }
        return $_POST[$key];
    }

    /**
     * Execute reinitialization process for this class
     *
     * @return  object   Current object of this class
     */
    public function initialize()
    {
        $this->keyword = $this->post('sSearch', '');
        $this->iSortCol_0 = $this->post('iSortCol_0', '');
        $this->sSortDir_0 = strtoupper($this->post('sSortDir_0', 'asc'));
        $this->page = (int) $this->post('iDisplayStart', 0);
        $this->pagesize = (int) $this->post('iDisplayLength', 10);
        return $this;
    }

    /**
     * Get the keyword value
     *
     * @return  string   will return keyword string value
     */
    public function keyword()
    {
        if (strlen($this->keyword)) {
            return $this->keyword;
        }
        return '';
    }

    /**
     * Get the page number
     *
     * @return  integer   will return current page number
     */
    public function page()
    {
        if ($this->page < 0) {
            $this->page = 0;
        }
        return abs($this->page);
    }

    /**
     * Get the row size per page
     *
     * @return  integer   will return current page size
     */
    public function pagesize()
    {
        if ($this->pagesize <= 0) {
            $this->pagesize = 10;
        }
        return abs($this->pagesize);
    }

    /**
     * Get or Set the column for sorting purpose
     *
     * @param   mixed     $default_sort_column  New default column name
     *
     * @return  integer   will return current page size
     */
    public function sort_column($default_sort_column = '')
    {
        $sort_column = '';
        if (is_int($default_sort_column) && $default_sort_column > 0) {
            $this->iSortCol_0 = $default_sort_column;
        }

        if (is_array($this->columns) && count($this->columns) && isset($this->columns[$this->iSortCol_0][0])) {
            $sort_column = $this->columns[$this->iSortCol_0][0];
        }

        return $sort_column;
    }

    /**
     * Get the sorting direction
     *
     * @return  string   will return current sorting direction method
     */
    public function sort_direction()
    {
        return $this->sSortDir_0 == self::ASCENDING ? self::ASCENDING : self::DESCENDING;
    }

    /**
     * Select the column name from current table for show up on datatable list
     *  This method can be executed more than one time
     *
     * @param   object  $db  Current database object
     *
     * @return  object  Current database object
     */
    public function selections($db)
    {
        foreach( $this->columns as $column) {
            $db->select_as($column[0], $column[1]);
        }
        return $db;
    }

    /**
     * Generates table header columns from defined $columns
     *  This method can be executed more than one time
     *
     * @param   string  $before  Before html tag wrapper, default `<th>`
     * @param   string  $after   After html tag wrapper, default `</th>`
     *
     * @return  string  Table row header in HTML format
     */
    public function table_headers($before='<th>', $after='</th>')
    {
        $html = '<tr>';
        foreach( $this->columns as $column) {
            $html .= "$before $column[2] $after";
        }
        $html .= '</tr>';

        return $html;
    }

    /**
     * Get html bootstrap label for `is_active` flag value
     *
     * @param  int     $value  `is_active` flag value
     *
     * @return string          Bootstrap label string
     */
    public function label_is_active($value)
    {
        if (!empty($value)) {
            return '<label class="label label-success">Aktif</label>';
        } else {
            return '<label class="label label-default">Tidak Aktif</label>';
        }
    }
}
