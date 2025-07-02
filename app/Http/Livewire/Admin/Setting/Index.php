<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Http\Livewire\Table\Lists;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class Index extends Lists
{
    public $roles;
    protected $listeners = ['reRenderParent'];
    public $search;

    public function render()
    {
        $this->roles = Role::orderBy('id', 'DESC')->get();

        return view('livewire.admin.setting.index');
    }

    public function mount()
    {
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function reRenderParent()
    {
        $this->render();
        $this->mount();
    }

    public function openRoleModal($role_id)
    {
        $this->emit('openRoleModal', $role_id);
    }

    public function loadData()
    {
        $roles = Role::orderBy('id', 'DESC')
            ->take($this->perPage * $this->page)
            ->get();

        $this->loadDataList($roles);
    }
}
