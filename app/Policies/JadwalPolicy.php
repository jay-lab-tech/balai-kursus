<?php

namespace App\Policies;

use App\Models\Jadwal;
use App\Models\User;

class JadwalPolicy
{
    /**
     * Before hook: allow admins to do everything
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') return true;
    }

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Jadwal $jadwal)
    {
        return $user->role === 'admin';
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Jadwal $jadwal)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Jadwal $jadwal)
    {
        return $user->role === 'admin';
    }
}
