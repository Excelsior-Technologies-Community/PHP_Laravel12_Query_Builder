@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Student Management</h2>

            <div>
                <a href="/students/trash" class="btn btn-outline-danger me-2">
                    Trash ({{ $trashedCount ?? 0 }})
                </a>

                <a href="/students/import" class="btn btn-outline-primary me-2">
                    Import CSV
                </a>

                <a href="{{ url('/students/export?search=' . request('search') . '&sort=' . request('sort') . '&min_age=' . request('min_age') . '&max_age=' . request('max_age') . '&date_from=' . request('date_from') . '&date_to=' . request('date_to') . '&name_start=' . request('name_start') . '&age_group=' . request('age_group') . '&format=json') }}"
                    class="btn btn-outline-info me-2" target="_blank">
                    Export JSON
                </a>

                <a href="{{ url('/students/export?search=' . request('search') . '&sort=' . request('sort') . '&min_age=' . request('min_age') . '&max_age=' . request('max_age') . '&date_from=' . request('date_from') . '&date_to=' . request('date_to') . '&name_start=' . request('name_start') . '&age_group=' . request('age_group')) }}"
                    class="btn btn-success me-2">
                    Export CSV
                </a>

                <a href="{{ url('/students/print?search=' . request('search') . '&sort=' . request('sort') . '&min_age=' . request('min_age') . '&max_age=' . request('max_age') . '&date_from=' . request('date_from') . '&date_to=' . request('date_to') . '&name_start=' . request('name_start') . '&age_group=' . request('age_group')) }}"
                    class="btn btn-outline-secondary me-2" target="_blank">
                    Print
                </a>

                <a href="/students/create" class="btn btn-primary">
                    + Add Student
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
        <form method="GET" action="/students" class="mb-4" id="filterForm">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-1">
                    <input type="text" name="name_start" class="form-control" placeholder="Name starts with..."
                        value="{{ request('name_start') }}" maxlength="1" style="text-transform: uppercase;">
                </div>

                <div class="col-md-2">
                    <select name="age_group" class="form-select">
                        <option value="">Age Group</option>
                        <option value="under_15" {{ request('age_group') == 'under_15' ? 'selected' : '' }}>Under 15</option>
                        <option value="15_18" {{ request('age_group') == '15_18' ? 'selected' : '' }}>15 - 18</option>
                        <option value="19_22" {{ request('age_group') == '19_22' ? 'selected' : '' }}>19 - 22</option>
                        <option value="23_25" {{ request('age_group') == '23_25' ? 'selected' : '' }}>23 - 25</option>
                        <option value="above_25" {{ request('age_group') == 'above_25' ? 'selected' : '' }}>Above 25</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" name="min_age" class="form-control" placeholder="Min Age"
                        value="{{ request('min_age') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="max_age" class="form-control" placeholder="Max Age"
                        value="{{ request('max_age') }}">
                </div>

                <div class="col-md-1">
                    <input type="date" name="date_from" class="form-control" placeholder="From"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-md-1">
                    <input type="date" name="date_to" class="form-control" placeholder="To"
                        value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort By</option>
                        <option value="age_asc" {{ request('sort') == 'age_asc' ? 'selected' : '' }}>Age (Low to High)</option>
                        <option value="age_desc" {{ request('sort') == 'age_desc' ? 'selected' : '' }}>Age (High to Low)</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (Oldest)</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (Newest)</option>
                        <option value="email_asc" {{ request('sort') == 'email_asc' ? 'selected' : '' }}>Email (A-Z)</option>
                        <option value="email_desc" {{ request('sort') == 'email_desc' ? 'selected' : '' }}>Email (Z-A)</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <select name="per_page" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">Search</button>
                </div>

                <div class="col-md-1">
                    <a href="/students" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <!-- Bulk Actions -->
        <form method="POST" action="/students/bulk-action" id="bulkForm">
            @csrf
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            <select name="action" class="form-select" id="bulkAction" required>
                                <option value="">Bulk Action</option>
                                <option value="delete">Move to Trash</option>
                                <option value="export">Export Selected</option>
                            </select>
                            <button type="submit" class="btn btn-warning" onclick="return confirmBulk()">Apply</button>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="40"><input type="checkbox" id="selectAll" onclick="toggleAll()"></th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Age</th>
                                <th>Created At</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td><input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-check"></td>
                                    <td>{{ $student->id }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->age }}</td>
                                    <td>{{ $student->created_at }}</td>
                                    <td>
                                        <a href="/students/show/{{ $student->id }}" class="btn btn-info btn-sm">View</a>
                                        <a href="/students/edit/{{ $student->id }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="/students/delete/{{ $student->id }}" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-danger">No students found.</td>
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
                                            <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.student-check');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        function confirmBulk() {
            const action = document.getElementById('bulkAction').value;
            if (!action) {
                alert('Please select an action');
                return false;
            }
            const checked = document.querySelectorAll('.student-check:checked');
            if (checked.length === 0) {
                alert('Please select at least one student');
                return false;
            }
            return confirm('Are you sure you want to perform this action on selected students?');
        }
    </script>

@endsection
