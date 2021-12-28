<?php

namespace App\Http\Livewire;

use App\Skill;
use App\Sortable;
use App\User;
use App\UserFilter;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refreshUserList' => 'refreshList',
    ];

    public $view;
    public $originalUrl;
    public $search;
    public $state;
    public $role;
    public $skills = [];
    public $from;
    public $to;
    public $order;

    protected $queryString = [
        'search' => ['except' => ''],
        'state' => ['except' => 'all'],
        'role' => ['except' => 'all'],
        'skills' => [],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'order' => ['except' => ''],
    ];

    public function mount($view, Request $request)
    {
        $this->view = $view;

        $this->originalUrl = $request->url();
    }

    public function updating()
    {
        $this->resetPage();
    }

    public function changeOrder($order)
    {
        $this->order = $order;
        $this->resetPage();
    }

    protected function getUsers(UserFilter $userFilter)
    {
        $users = User::query()
            ->with('team', 'skills', 'profile.profession')
            ->when(request('team'), function ($query, $team) {
                if ($team === 'with_team') {
                    $query->has('team');
                } elseif ($team === 'without_team') {
                    $query->doesntHave('team');
                }
            })
            ->filterBy($userFilter, array_merge(
                ['trashed' => request()->routeIs('users.trashed')],
                [
                    'state' => $this->state,
                    'role' => $this->role,
                    'search' => $this->search,
                    'skills' => $this->skills,
                    'from' => $this->from,
                    'to' => $this->to,
                    'order' => $this->order,
                    'direction' => request('direction')
                ]

            ))
            ->orderByDesc('created_at')
            ->paginate();

        $users->appends($userFilter->valid());

        return $users;
    }

    public function render(UserFilter $userFilter)
    {
        $sortable = new Sortable($this->originalUrl);

        $this->view = 'index';

        return view('users._livewire-list', [
            'users' => $this->getUsers($userFilter),
            'view' => $this->view,
            'checkedSkills' => collect(request('skills')),
            'sortable' => $sortable,
        ]);
    }

    public function refreshList($field, $value, $checked = true)
    {
        if (in_array($field, ['search', 'state', 'role', 'from', 'to'])) {
            $this->$field = $value;
        }

        if ($field === 'skills') {
            if ($checked) {
                $this->skills[$value] = $value;
            } else {
                unset($this->skills[$value]);
            }
        }
    }
}
