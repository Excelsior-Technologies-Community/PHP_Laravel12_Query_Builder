@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h2 class="fw-bold mb-4">Import Students</h2>

    <a href="/students" class="btn btn-secondary mb-3">Back to Students</a>

    <div class="card shadow border-0">
        <div class="card-body">
            <form method="POST" action="/students/import" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Select CSV File</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <div class="form-text">
                        CSV format: Name, Email, Age (one student per row). Duplicate emails will be skipped.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Import</button>
                <a href="/students" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
