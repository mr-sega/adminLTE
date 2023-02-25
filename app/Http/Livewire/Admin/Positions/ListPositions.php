<?php

namespace App\Http\Livewire\Admin\Positions;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

class ListPositions extends Component
{
    public $state = [];

    public $showEditModal = false;

    public $positionIdBeingRemoved = null;

    public $searchTerm = null;

    public function addNewPosition()
    {
        $this->dispatchBrowserEvent('show-form');
    }

    public function createPosition()
    {
        $this->showEditModal = false;

        $position = Validator::make($this->state, [
            'name' => 'required',
        ])->validate();

        Position::create($position);

        $this->dispatchBrowserEvent('hide-form', ['message' => 'Position added successfully!']);

        return redirect()->back();
    }

    public function edit(Position $position)
    {

        $this->showEditModal = true;

        $this->position = $position;

        $this->state = $position->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    public function updatePosition()
    {
        $position = Validator::make($this->state, [
            'name' => 'required',
        ])->validate();

        $this->position->update($position);

        $this->dispatchBrowserEvent('hide-form', ['message' => 'Position update successfully!']);

        return redirect()->back();
    }

    public function confirmPositionRemoval($positionId)
    {
        $this->positionIdBeingRemoved = $positionId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function deletePosition()
    {
        $position = Position::findOrFail($this->positionIdBeingRemoved);

        $position->delete();

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'Position delete successfully!']);
    }

    public function render()
    {
        $positions = Position::query()
            ->where('name', 'like', '%'.$this->searchTerm. '%')
            ->latest()
            ->paginate();
        return view('livewire.admin.positions.list-positions', [
            'positions' => $positions,
        ]);
    }
}
