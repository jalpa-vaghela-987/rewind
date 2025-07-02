<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;

class Lists extends Component
{
    public $page = 1;
    public $perPage = 15;
    public $hasMorePages;
    public $lists;

    public function loadDataList($lists)
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }

        $this->loadPage = true;

        $this->lists = $lists;
        $this->page++;

        $this->hasMorePages = (bool)count($lists);

    }

}
