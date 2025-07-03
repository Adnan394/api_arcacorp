@extends('layouts.admin')
@section('content')
<main id="main" class="main">
    
    <div class="pagetitle">
      <h1>Data User</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Data User</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Data</h5>
              <a href="{{ route('user.create') }}" class="btn btn-primary justify-content-end mb-3">Tambah Data User</a>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->username }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->role ? $item->role->name : '-' }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $item->deleted_at ? 'bg-danger' : 'bg-success' }}">{{ $item->deleted_at ? 'Tidak Aktif' : 'Aktif' }}</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-start gap-2">
                                <a href="{{ route('user.edit', $item->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @if ($item->deleted_at)
                                  <a href="{{ route('user_restore', $item->id) }}" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i></a>
                                @endif
                            </div>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>
</main>
@endsection