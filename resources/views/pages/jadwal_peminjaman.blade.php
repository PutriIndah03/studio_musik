@extends('layouts.app')

@section('content')
<br>
<link rel="stylesheet" href="{{ asset('css/jadwal_peminjaman.css') }}">

<h2>Jadwal Peminjaman Studio Musik</h2>

<div id="calendar-container">
    <div id="calendar"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($events),
            // eventColor: '#007bff',
            // eventTextColor: '#ffffff'
        });
        calendar.render();
    });
</script>
@endsection
