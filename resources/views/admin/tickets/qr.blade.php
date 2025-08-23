@extends('layouts.admin.admin')
@section('content')
<div class="container py-5 text-center">
    <h2>QR Vé</h2>
    <p><strong>Mã vé:</strong> {{ $ticket->ticket_code }}</p>
    @if($qrCodeBase64)
        <img src="{{ $qrCodeBase64 }}" alt="QR Vé" style="max-width:300px;">
        <br>
    <a href="{{ route('tickets.print', ['ticket_id' => $ticket->id]) }}" class="btn btn-success mt-3" target="_blank">Tải PDF vé</a>
    @else
        <p class="text-danger">Không tạo được mã QR!</p>
    @endif
</div>
@endsection