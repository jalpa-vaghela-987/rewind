<?php

namespace App\Http\Livewire\Admin\Notification;

use App\Http\Livewire\Table\Lists;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Collection;

class Index extends Lists
{

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $editing = false;
    public $item;
    public $message = '';
//    public $userSelectedId = [];
    public $showModal = false;
    public $showFormModal = false;
    public $showDeleteModal = false;
    public $selectedItem;
    public $showInput = false;

    protected $listeners = ['reRenderParent'];

    public function openViewModal(Notification $notification)
    {
        $this->selectedItem = $notification;
        $this->showModal = true;
    }

    public function openFormModal($itemId = null)
    {
        $this->editing = $itemId ? true : false;

        if ( $this->editing ) {
            $this->item = Notification::find($itemId);
            $this->message = $this->item->message;
        } else {
            $this->item = null;
            $this->message = '';
        }

        $this->showFormModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showFormModal = false;
        $this->showDeleteModal = false;
        $this->selectedItem = null;
    }

    public function openDeleteModal(Notification $notification)
    {
        $this->selectedItem = $notification;
        $this->showDeleteModal = !$this->showDeleteModal;
    }

    public function openModal($itemId = null)
    {
        $this->editing = $itemId ? true : false;

        if ( $this->editing ) {
            $this->item = Notification::find($itemId);
            $this->name = $this->item->name;
        } else {
            $this->item = null;
            $this->name = '';
        }


        $this->showModal = true;
    }

//    public function clearSelection()
//    {
//        $this->userSelectedId = [];
//    }

    public function render()
    {
//        $users = User::query()->whereNotIn('id', [auth()->id()])->get();
        $items = Notification::query()->whereHas('sender.roles', function ($role) {
            $role->where('name', ROLE_ADMIN);
        })->orderByDesc('id')->paginate(10);
        return view('livewire.admin.notification.index', [
            'items' => $items,
        ]);
    }

    public function reRenderParent()
    {
        $this->closeModal();
        $this->render();
    }

    public function confirmDelete()
    {
        if ( $this->selectedItem ) {
            $notification = Notification::find($this->selectedItem->id);
            $notification->delete();
            $type = 'success';
            $msg = 'Notification deleted successfully!';
            $this->emitTo('flash-component', 'flashMessage', ['type' => $type, 'msg' => $msg]);
        }

        $this->reRenderParent();
    }

//    public function validationRules()
//    {
//        if ( $this->editing ) {
//            return [
//                'message' => ['required'],
//            ];
//        } else {
//            return [
//                'userSelectedId' => ['required', 'array', 'min:1'],
//                'message'        => 'required',
//            ];
//        }
//    }

//    protected $messages = [
//        'userSelectedId.required' => 'The user field is required.',
//        'userSelectedId.min'      => 'The user must be at least :min characters.',
//    ];

    public function saveItem()
    {
        $this->validate([
            'message' => 'required',
        ]);


        $msg = 'Notification send successfully!';
        foreach (User::query()->whereNotIn("id", [auth()->id()])->get() as $user) {
            $user->notify(new SendMessageNotification($this->message));
        }

        $type = 'success';
        $this->emitTo('flash-component', 'flashMessage', ['type' => $type, 'msg' => $msg]);

        $this->reRenderParent();
    }

    public function mount(){
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function loadData()
    {
        $items = Notification::query()->whereHas('sender.roles', function ($role) {
            $role->where('name', ROLE_ADMIN);
        })->orderByDesc('id')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($items);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }
}
