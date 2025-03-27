@extends('layouts.app')

@section('content')
<br>
<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Poppins', sans-serif;
    }
    
    #calendar-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        font-weight: bold;
        color: #333;
    }

    #calendar {
        padding: 10px;
        border-radius: 10px;
        background-color: #ffffff;
    }

    .fc-toolbar-title {
        font-size: 20px !important;
        font-weight: bold;
        color: #2c3e50;
    }

    .fc-daygrid-day-number {
        font-size: 14px !important;
        font-weight: 600;
        color: #555;
    }

    .fc-event {
        border-radius: 5px !important;
        padding: 5px;
    }

    .fc-button {
        background: #007bff !important;
        border: none !important;
        color: #fff !important;
        font-size: 14px !important;
        padding: 8px 12px !important;
        border-radius: 5px !important;
    }

    .fc-button:hover {
        background: #0056b3 !important;
    }
</style>

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
