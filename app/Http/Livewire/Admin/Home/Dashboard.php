<?php

namespace App\Http\Livewire\Admin\Home;
use Livewire\Component;

class Dashboard extends Component
{
    protected $listneres    =   ['reRenderParent'];
    public function render()
    {
        return view('livewire.admin.home.dashboard');
    }
    public function reRenderParent(){
        $this->render();
    }
}
