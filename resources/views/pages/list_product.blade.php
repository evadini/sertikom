@extends('layouts.list')

@section('title', 'Daftar Produk - My App')

@section('content')
<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Produk</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $post)
            <tr>
                <td>{{ $post['id'] }}</td>
                <td>{{ $post['produk'] }}</td>
                </tr>
        @endforeach
    </tbody>
</table>
@endsection
