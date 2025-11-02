<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'is_active'
    ];



    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi
    public function assignmentsAsStaff()
    {
        return $this->hasMany(Assignment::class, 'staff_id');
    }

    public function assignmentsAsDriver()
    {
        return $this->hasMany(Assignment::class, 'driver_id');
    }

    public function assignmentsAsGuide()
    {
        return $this->hasMany(Assignment::class, 'guide_id');
    }

    public function workSessions()
    {
        return $this->hasMany(WorkSession::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    public function monthlyHoursAsDriver($year = null, $month = null)
    {
        $year = $year ?: now()->year;
        $month = $month ?: now()->month;

        return Assignment::where('driver_id', $this->id)
            ->whereYear('scheduled_start', $year)
            ->whereMonth('scheduled_start', $month)
            ->whereIn('status', ['assigned','in_progress','completed'])
            ->selectRaw('COALESCE(SUM(estimated_hours),0) as total')
            ->value('total') ?: 0;
    }

    public function monthlyHoursAsGuide($year = null, $month = null)
    {
        $year = $year ?: now()->year;
        $month = $month ?: now()->month;

        return Assignment::where('guide_id', $this->id)
            ->whereYear('scheduled_start', $year)
            ->whereMonth('scheduled_start', $month)
            ->whereIn('status', ['assigned','in_progress','completed'])
            ->selectRaw('COALESCE(SUM(estimated_hours),0) as total')
            ->value('total') ?: 0;
    }

    /**
     * Helper umum: cek apakah user punya limit dan masih bisa menampung tambahan jam
     */
    public function canAcceptAdditionalHours(float $additionalHours, string $role = 'driver', $year = null, $month = null): bool
    {
        $year = $year ?: now()->year;
        $month = $month ?: now()->month;

        // jika tidak ada limit => dianggap unlimited
        if (is_null($this->monthly_hours_limit)) {
            return true;
        }

        $used = $role === 'guide'
            ? $this->monthlyHoursAsGuide($year, $month)
            : $this->monthlyHoursAsDriver($year, $month);

        return ($used + $additionalHours) <= (float) $this->monthly_hours_limit;
    }
}
