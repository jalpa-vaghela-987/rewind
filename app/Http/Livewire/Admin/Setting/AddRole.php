<?php

namespace App\Http\Livewire\Admin\Setting;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
class AddRole extends Component
{
    protected $listeners = ["openRoleModal"];

    public $showModal=false;
    public $name;
    public $role_id;
    public $permissions;
    public $title;
    public $role_permissions = [];

    public function rules()
    {
        return [
            'name' => ['required'],
            'role_permissions'=>['required','array']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Project name required.',
        ];
    }

    public function render()
    {
        $this->permissions = Permission::get();
        return view('livewire.admin.setting.add-role');
    }

    public function mount(){

    }
    public function openRoleModal($role_id=null){

        if($role_id){
            $this->title='Edit';
            $this->role_id= $role_id;
            $role= Role::findorFail($role_id);
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role_id)
                ->pluck('role_has_permissions.permission_id')
                ->all();
            $this->name =$role->name;
            $this->role_permissions = $rolePermissions;
        }else{
            $this->title='Add';
            $this->name = null;
            $this->role_permissions = [];
        }


        $this->showModal = true;



    }
    public function closeRoleModal(){
        $this->showModal = false;
        $this->role_permissions = [];
        $this->name = null;

    }
    public function saveRole(){
        $this->validate();

        if($this->role_id){
            $role = Role::find($this->role_id);
            $role->name= $this->name;
            $role->save();
            if(isset($this->role_permissions)){
                $role->syncPermissions($this->role_permissions);
            }
        }else{
            $role = Role::create(['name' => $this->name]);
            if(isset($this->role_permissions)){
                $role->syncPermissions($this->role_permissions);
            }
        }

        $this->closeRoleModal();
        $this->emit('reRenderParent');
    }
}
