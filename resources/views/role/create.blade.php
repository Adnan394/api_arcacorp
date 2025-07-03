@extends('layouts.admin')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Add Data Role</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Data Role</li>
                    <li class="breadcrumb-item active">Add Data Role</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="row">
                    <div class="col">
                        <div class="card p-3">
                            <div class="card-body">
                                <h5 class="card-title">Add Data</h5>
                                <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" id="name">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('role.index') }}" type="button" class="btn btn-secondary">cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

