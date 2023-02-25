<?php

namespace App\Http\Livewire\Admin\Employees;


use App\Models\Employee;
use App\Models\Head;
use App\Models\Position;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;


class ListEmployees extends Component
{
    use WithFileUploads;

    public $state = [];

    public $employee;

    public $showEditModal = false;

    public $employeeIdBeingRemoved = null;

    public $photo;

    public $searchTerm = null;

    public function addNew()
    {
        $this->reset();

        $this->showEditModal = false;

        $this->dispatchBrowserEvent('show-form');
    }

    public function createEmployee()
    {
      $employee = Validator::make($this->state, [
            'name' => 'required', 'min:2', 'max:255',
            'position_id' => 'required',
            'date_of_employment' => 'required',
            'email' => 'required|email|unique:employees',
            'phone_number' => 'required|regex:/^\+380\d{3}\d{2}\d{2}\d{2}$/',
            'salary' => 'required|numeric',
        ])->validate();

        if ($this->photo)
            $employee['photo'] = $this->photo->store('/', 'photos');


      Employee::create($employee);

      $this->dispatchBrowserEvent('hide-form', ['message' => 'Employee added successfully!']);

      return redirect()->back();
    }

    public function edit(Employee $employee)
    {
        $this->reset();

        $this->showEditModal = true;

        $this->employee = $employee;

        $this->state = $employee->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    public function updateEmployee()
    {
        $employee = Validator::make($this->state, [
            'photo' => 'required',
            'name' => 'required',
            'position_id' => 'required',
            'date_of_employment' => 'required',
            'email' => 'required|email|unique:employees,email,'.$this->employee->id,
            'phone_number' => 'required',
            'salary' => 'required',
        ])->validate();

        if ($this->photo)
            !is_null($this->employee->photo) && Storage::disk('photos')->delete($this->employee->photo);
            $employee['photo'] = $this->photo->store('/', 'photos');

        $this->employee->update($employee);

        $this->dispatchBrowserEvent('hide-form', ['message' => 'Employee update successfully!']);

        return redirect()->back();
    }

    public function confirmEmployeeRemoval($employeeId)
    {
        $this->employeeIdBeingRemoved = $employeeId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function deleteEmployee()
    {
        $employee = Employee::findOrFail($this->employeeIdBeingRemoved);

        $employee->delete();

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'Employee deleted successfully!']);
    }

    public function render()
    {
        $employees = Employee::query()
            ->where('name', 'like', '%'.$this->searchTerm. '%')
            ->orWhere('salary', 'like', '%'.$this->searchTerm. '%')
            ->orWhere('date_of_employment', 'like', '%'.$this->searchTerm. '%')
            ->orWhere('phone_number', 'like', '%'.$this->searchTerm. '%')
            ->orWhere('email', 'like', '%'.$this->searchTerm. '%')
            ->latest()
            ->paginate(10);

        $positions = Position::all();

        return view('livewire.admin.employees.list-employees',[
            'employees' => $employees,
            'positions' => $positions,
        ]);
    }
}
