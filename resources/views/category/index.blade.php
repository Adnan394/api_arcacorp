@extends('layouts.admin')
@section('content')
<main id="main" class="main">
    
    <div class="pagetitle">
      <h1>Data Category</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Data Category</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Data</h5>
              <a href="{{ route('category.create') }}" class="btn btn-primary justify-content-end mb-3">Tambah Data Category</a>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Limit per Month</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->name }}</td>
                        <td>Rp {{ number_format($item->limit_per_month, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $item->deleted_at ? 'bg-danger' : 'bg-success' }}">{{ $item->deleted_at ? 'Tidak Aktif' : 'Aktif' }}</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-start gap-2">
                                <a href="{{ route('category.edit', $item->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('category.destroy', $item->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @if ($item->deleted_at)
                                  <a href="{{ route('category_restore', $item->id) }}" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i></a>
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