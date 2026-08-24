@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Trash</h2>
        <a href="/students" class="btn btn-primary">Back to Students</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" action="/students/trash" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="/students/trash" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    <div class="card shadow border-0">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>Deleted At</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->age }}</td>
                            <td>{{ $student->deleted_at }}</td>
                            <td>
                                <a href="/students/restore/{{ $student->id }}" class="btn btn-success btn-sm"
                                    onclick="return confirm('Restore this student?')">Restore</a>
                                <a href="/students/force-delete/{{ $student->id }}" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Permanently delete this student?')">Delete Permanently</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">Trash is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($students->lastPage() > 1)
                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        <ul class="pagination custom-pagination">
                            @for ($i = 1; $i <= $students->lastPage(); $i++)
                                <li class="page-item {{ $students->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
