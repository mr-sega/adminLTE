<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Employees</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Employees</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <button wire:click.prevent="addNew" class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i>Add new employees</button>
                        <div>
                            <input wire:model="searchTerm" type="text" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Data of employment</th>
                                    <th scope="col">Phone number</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Salary, $</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th scope="col">Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($employees as $employee)
                                <tr>
                                    <th scope="row">{{$employee->id}}</th>
                                    <td><img src="{{ $employee->photo_url }}" style="width: 50px;" class="img img-circle mr-1" alt=""></td>
                                    <td>{{$employee->name}}</td>
                                    <td>{{$employee->position->name}}</td>
                                    <td>{{$employee->date_of_employment}}</td>
                                    <td>{{$employee->phone_number}}</td>
                                    <td>{{$employee->email}}</td>
                                    <td>{{$employee->salary}}</td>
                                    <td>{{$employee->created_at}}</td>
                                    <td>{{$employee->updated_at}}</td>
                                    <td>
                                        <a href="" wire:click.prevent="edit({{ $employee }})">
                                            <i class="fa fa-edit mr-2"></i>
                                        </a>
                                        <a href="" wire:click.prevent="confirmEmployeeRemoval({{ $employee->id }})">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="5">No results found</td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $employees->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <!-- Modal -->
    <div class="modal fade" id="form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateEmployee' : 'createEmployee' }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        @if($showEditModal)
                        <span>Edit Employee</span>
                        @else
                        <span>Add New Employee</span>
                        @endif
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="customFile">Profile Photo</label>
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="img img-thumbnail d-block mb-2"  style="width: 100px;"
                                alt="">
                            @else
                                <img src="{{ $state['photo_url'] ?? ''}}" class="img img-thumbnail d-block mb-2"  style="width: 100px;"
                                alt="">
                            @endif
                        <div class="custom-file">
                            <label for="photo" class="custom-file-label">
                                @if ($photo)
                                    {{ $photo->getClientOriginalName() }}
                                @else
                                Choose file
                                @endif
                            </label>
                            <input wire:model="photo" class="custom-file-input @error('photo') is-invalid @enderror"  type="file" id="photo">
                            @error('photo')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" wire:model="state.name" class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" placeholder="Enter full name">
                            @error('name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="position_id" class="col-sm-2 col-form-label">Position:</label>
                            <div class="col-sm-6">
                                <select wire:model="state.position_id" name="position_id" id="position_id" class="form-control">
                                    @foreach($positions as $position)
                                        <option value="{{$position->id}}"
                                                @isset($employee)
                                                @if($employee->position_id == $position->id)
                                                selected
                                            @endif
                                            @endisset
                                        >{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="date_of_employment" class="form-label">Data of employment</label>
                            <input data-date-format="DD-MM-YY" wire:model="state.date_of_employment" type="date" class="form-control  @error('date_of_employment') is-invalid @enderror" id="date_of_employment" name="date_of_employment" placeholder="{{old('date_of_employment')}}" value="{{old('date_of_employment')}}">
                            @error('date_of_employment')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" wire:model="state.email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                            @error('email')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone number</label>
                            <input type="text"  wire:model="state.phone_number" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" placeholder="+380">
                            @error('phone_number')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="text" wire:model="state.salary" class="form-control @error('salary') is-invalid @enderror" id="salary" placeholder="100">
                            @error('salary')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        @if($showEditModal)
                        <span>Save Changes</span>
                        @else
                        <span>Save</span>
                        @endif
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header"></div>
                <h5> Delete Employee </h5>
                <div class="modal-body">
                    <h4>Are you sure you want to delete this employee?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click.prevent="deleteEmployee" class="btn btn-danger">Delete</button>
                </div>
            </div>
</div>
    </div>
        </div>
