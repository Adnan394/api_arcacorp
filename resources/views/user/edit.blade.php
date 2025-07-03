@extends('layouts.admin')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Data User</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Data User</li>
                    <li class="breadcrumb-item active">Edit Data User</li>
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
                                <h5 class="card-title">Edit Data</h5>
                                <form action="{{ route('user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" name="email" class="form-control" id="email" value="{{ $data->email }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" name="username" class="form-control" id="username" value="{{ $data->username }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="role_id" class="form-label">Role</label>
                                                <select class="form-select" name="role_id" id="role_id">
                                                    <option value="">Select Role</option>
                                                    @foreach ($role as $r)
                                                    <option value="{{ $r->id }}" {{ $data->role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control" id="password" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('user.index') }}" type="button" class="btn btn-secondary">cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

