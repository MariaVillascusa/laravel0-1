<div id="users-table" class="table-responsive-lg">
    @if( $users->count() )
        <table class="table table-sm">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                @foreach(['first_name' => 'Nombre', 'email' => 'Correo', 'date' => 'Registro', 'login' => 'Último login'] as $column => $title)
                    <th scope="col">
                        <a wire:click.prevent="changeOrder('{{ $sortable->order($column) }}')"
                           href="{{ $sortable->url($column) }}" class="{{ $sortable->classes($column) }}">
                            {{ $title }} <i class="icon-sort"></i>
                        </a>
                    </th>
                @endforeach
                <th scope="col" class="text-right th-actions">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @each('users._row', $users, 'user')
            </tbody>
        </table>
        {{ $users->links() }}
    @else
        <p>No hay usuarios registrados</p>
    @endif
</div>

