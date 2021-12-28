@extends('livewire-layout')

@section('title', 'Usuarios')

@section('content')
    <h1>{{ trans('users.title.' . $view) }}</h1>
    <p>
        @if ($view == 'index')
            <a href="{{ route('users.trashed') }}" class="btn btn-outline-dark">Ver papelera</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Nuevo usuario</a>
        @else
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Regresar al listado de usuarios</a>
        @endif
    </p>
    @if ($view === 'index')
        @livewire('user-filter')
    @endif
    @livewire('users-list', compact(['view']))

@endsection

@push('scripts')
    <script>
        let loadCalendars = function() {

            $('#btn-filter').hide();

            ['from', 'to'].forEach(field => {
                $('#' + field).datepicker({
                    uiLibrary: 'bootstrap4',
                    format: 'dd/mm/yyyy'
                }).on('change', function() {
                    let usersTableId = $('#users-table').attr('wire:id');
                    let usersTable = window.livewire.find(usersTableId);

                    if (usersTable.get(field) !== $(this).val()) {
                        window.livewire.emit('refreshUserList', field, $(this).val());
                    }
                });
            });
        };

        loadCalendars();

        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', loadCalendars)
        })
    </script>
@endpush
