<?php
class Paginator
{
    public $limit;
    public $offset;
    public $next;
    public $previous;
    public function __construct($page, $recodrds_per_page, $total_records)
    {
        $this->limit = $recodrds_per_page;
        $page = filter_var($page, FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 1,
                'min_range' => 1
            ]
        ]);
        $this->previous = $page - 1;
        $total_records = 18;
        $total_pages = ceil($total_records / $recodrds_per_page);
        if ($page <  $total_pages) {
            $this->next = $page + 1;
        }
        $this->offset = $recodrds_per_page * ($page - 1);
    }
}