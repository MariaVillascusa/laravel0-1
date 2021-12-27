<form method="get" action="{{ route('users.index') }}">
    <div class="row row-filters">
        <div class="col-12">
            @foreach(['' => 'Todos', 'with_team' => 'Con equipo', 'without_team' => 'Sin equipo'] as $value => $text)
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="team" id="team_{{ $value ?: 'all' }}"
                       value="{{ $value }}" {{ $value === request('team', '') ? 'checked' : '' }}>
                <label class="form-check-label" for="team_{{ $value ?: 'all' }}">{{ $text }}</label>
            </div>
            @endforeach
        </div>
    </div>
    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('users.filters.states') as $value => $text)
                <div class="form-check form-check-inline">
                    <input wire:model="state" type="radio" class="form-check-input" name="state" id="state_{{ $value ?: 'all' }}"
                           value="{{ $value }}" {{ $value === $state ? 'checked' : '' }}>
                    <label class="form-check-label" for="state_{{ $value ?: 'all' }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row row-filters">
        <div class="col-md-6">
            <div class="form-inline form-search">
                <div class="input-group">
                    <input wire:model.debounce.1000ms="search" type="search" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Buscar...">
                </div>

                <div class="btn-group">
                    <select wire:model="role" name="role" id="role" class="select-field">
                        @foreach(trans('users.filters.roles') as $value => $text)
                            <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $text }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="btn-group drop-skills">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Habilidades
                    </button>
                    <div class="dropdown-menu skills-list">
                        @foreach($skillsList as $skill)
                            <div class="form-group form-check">
                                <input wire:model="skills" name="skills[]" type="checkbox" class="form-check-input"
                                       id="skill_{{ $skill->id }}" value="{{ $skill->id }}"
                                       {{ in_array($skill->id, $skills) ? 'checked' : '' }}>
                                <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 text-right">
            <div class="form-inline form-dates">
                <label for="date_start" class="form-label-sm">Fecha</label>
                <div class="input-group">
                    <input wire:model="from" type="text" class="form-control form-control-sm" name="from" id="from"
                           value="{{ $from }}" placeholder="Desde">
                </div>
                <div class="input-group">
                    <input wire:model="to" type="text" class="form-control form-control-sm" name="to" id="to"
                           value="{{ $to }}" placeholder="Hasta">
                </div>
                &nbsp;
                <button type="submit" class="btn btn-sm btn-primary" id="btn-filter">Filtrar</button>
            </div>
        </div>
    </div>
</form>
