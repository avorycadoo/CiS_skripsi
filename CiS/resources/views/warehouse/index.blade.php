@extends('layouts/conquer');
@section('content')
    @if (session('status'))
        <div class ="alert alert-success"> {{ session('status') }}</div>
    @endif

    <a href={{ route('warehouse.create') }} class= "btn btn-success" style="background-color: #000000; color: white;"> + Warehouse </a>

    <table class="table">
        <thead>
            <tr>
                <th>Warehouse Name</th>
                <th>Warehouse Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr id="tr_{{ $d->id }}">
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->address }}</td>
                    <td><a class="btn btn-warning" href="{{ route('warehouse.edit', $d->id) }}" style="background-color: #000000; color: white;"> Edit</a>
                        <form method="POST" action="{{ route('warehouse.destroy', $d->id) }}">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="delete" class="btn btn-danger"
                                onclick="return confirm('Are you sure to delete {{ $d->id }} - {{ $d->name }} ? ');">
                        </form>
                    </td>
                </tr>
            @endforeach
    </table>
@endsection

<div class="modal fade" id="modalEditA" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-body" id="modalContent">
                {{-- You can put animated loading image here... --}}
            </div>
        </div>
    </div>
</div>


@section('js')
    
@endsection
