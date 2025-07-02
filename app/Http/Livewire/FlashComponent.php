<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FlashComponent extends Component
{
    public $type,$msg,$showMessage=false;
    protected $listeners = ['flashMessage'=>'showFlashMessage'];
    public function render()
    {
        return view('livewire.flash-component');
    }
    public function reRenderComponent(){
        $this->render();
    }
    public function showFlashMessage($param){
        $this->type = $param['type']=='error'?'danger':$param['type'];
        $this->msg  = $param['msg'];
        $this->showMessage = true;
        $this->emit('hideFlashMsg');
    }
    public function hideFlashMessage(){
        $this->type = null;
        $this->msg = null;
        $this->showMessage = false;
    }
}
