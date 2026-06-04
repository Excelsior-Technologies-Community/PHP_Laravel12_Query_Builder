@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Student Management</h2>

            <div>
                <a href="{{ url('/students/export?search=' . request('search') . '&sort=' . request('sort') . '&min_age=' . request('min_age') . '&max_age=' . request('max_age')) }}"
                    class="btn btn-success me-2">
                    Export CSV
                </a>

                <a href="/students/create" class="btn btn-primary">
                    + Add Student
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif

        <!-- Dashboard Statistics -->

        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h6>Total Students</h6>
                        <h2>{{ $totalStudents }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h6>Average Age</h6>
                        <h2>{{ round($averageAge) }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h6>Youngest</h6>
                        <h2>{{ $youngest }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h6>Oldest</h6>
                        <h2>{{ $oldest }}</h2>
                    </div>
                </div>
            </div>

        </div>

        <!-- Search + Filter + Sort -->

        <form method="GET" action="/students" class="mb-4">

            <div class="row g-2">

                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="min_age" class="form-control" placeholder="Min Age"
                        value="{{ request('min_age') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="max_age" class="form-control" placeholder="Max Age"
                        value="{{ request('max_age') }}">
                </div>

                <div class="col-md-2">
                    <select name="sort" class="form-select">

                        <option value="">
                            Sort Age
                        </option>

                        <option value="age_asc" {{ request('sort') == 'age_asc' ? 'selected' : '' }}>
                            Low → High
                        </option>

                        <option value="age_desc" {{ request('sort') == 'age_desc' ? 'selected' : '' }}>
                            High → Low
                        </option>

                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-success w-100">
                        Search
                    </button>
                </div>

            </div>

        </form>

        <!-- Students Table -->

        <div class="card shadow border-0">

            <div class="card-body">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th width="220">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $student)

                            <tr>

                                <td>{{ $student->id }}</td>

                                <td>{{ $student->name }}</td>

                                <td>{{ $student->email }}</td>

                                <td>{{ $student->age }}</td>

                                <td>

                                    <a href="/students/show/{{ $student->id }}" class="btn btn-info btn-sm">
                                        View
                                    </a>

                                    <a href="/students/edit/{{ $student->id }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="/students/delete/{{ $student->id }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this student?')">
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center text-danger">

                                    No students found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

                <!-- Pagination -->

                @if($students->lastPage() > 1)

                    <div class="d-flex justify-content-center mt-4">

                        <nav>

                            <ul class="pagination custom-pagination">

                                @for ($i = 1; $i <= $students->lastPage(); $i++)

                                    <li class="page-item {{ $students->currentPage() == $i ? 'active' : '' }}">

                                        <a class="page-link" href="{{ $students->url($i) }}">

                                            {{ $i }}

                                        </a>

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