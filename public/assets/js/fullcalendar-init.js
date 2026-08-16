// Inicialização do FullCalendar

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('fullcalendar-agenda');
    if (!calendarEl) return;
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: window.fcEvents || [],
        eventColor: '#0d6efd',
        eventClick: function(info) {
            window.location.href = 'agendamentos/edit/' + info.event.id;
        },
        dateClick: function(info) {
            window.location.href = 'agendamentos/create?data=' + info.dateStr;
        },
        eventDidMount: function(info) {
            var tooltip = new bootstrap.Tooltip(info.el, {
                title: info.event.extendedProps.tooltip,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        },
        eventClassNames: function(arg) {
            if(arg.event.extendedProps.status === 'realizado') return ['bg-success', 'text-white'];
            if(arg.event.extendedProps.status === 'cancelado') return ['bg-danger', 'text-white'];
            return ['bg-primary', 'text-white'];
        },
        height: 'auto',
        aspectRatio: 1.5,
    });
    calendar.render();
}); 