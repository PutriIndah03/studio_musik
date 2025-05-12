@extends('layouts.app')

@section('content')
<br>
<link rel="stylesheet" href="{{ asset('css/jadwal_peminjaman.css') }}">
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.min.js'></script>

<h2>Jadwal Peminjaman Studio Musik</h2>

<div id="calendar-container">
    <div id="calendar"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                @foreach($calendarEvents as $event)
                {
                    title: `{!! collect(explode('<br>', $event['title']))->map(function($item, $index) {
                        return ($index + 1) . '. ' . trim($item);
                    })->implode("\n") !!}`,
                    start: '{{ $event['start'] }}',
                    end: '{{ \Carbon\Carbon::parse($event['start'])->addDay()->toDateString() }}',
                    display: 'background',
                    backgroundColor: '#FF6363', // Warna latar belakang
                    textColor: '#000000', // Warna teks menjadi hitam
                },
                @endforeach
            ],
            eventRender: function(info) {
                // Menambahkan gaya CSS langsung
                $(info.el).css('color', 'black');  // Menyeting warna teks hitam
                $(info.el).css('font-size', '5px');  // Menurunkan ukuran font
            }
        });
        calendar.render();
    });
</script>

@endsection
