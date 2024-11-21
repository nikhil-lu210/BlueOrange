<?php

namespace App\Models\IncomeExpense;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\IncomeExpense\Relations\IncomeExpenseCategoryRelations;

class IncomeExpenseCategory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, IncomeExpenseCategoryRelations, HasCustomRouteId;
    
    protected $cascadeDeletes = ['incomes', 'expenses'];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
    ];

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
}
