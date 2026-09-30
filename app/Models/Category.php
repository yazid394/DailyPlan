<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'color_code'])]
class Category extends Model
{
    /**
     * The user who owns this category.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The tasks in this category.
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The recurring schedules in this category.
     *
     * @return HasMany<RecurringSchedule, $this>
     */
    public function recurringSchedules(): HasMany
    {
        return $this->hasMany(RecurringSchedule::class);
    }
}
