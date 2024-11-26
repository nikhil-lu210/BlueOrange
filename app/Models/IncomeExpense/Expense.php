<?php

namespace App\Models\IncomeExpense;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\IncomeExpense\Mutators\ExpenseMutators;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\IncomeExpense\Accessors\ExpenseAccessors;
use App\Models\IncomeExpense\Relations\ExpenseRelations;

class Expense extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use ExpenseRelations;

    // Accessors & Mutators
    use ExpenseAccessors, ExpenseMutators;
    
    protected $cascadeDeletes = [];

    protected $casts = [
        'description' => PurifyHtmlOnGet::class,
        'date' => 'date',
    ];

    protected $fillable = [
        'creator_id',
        'category_id',
        'title',
        'date',
        'quantity',
        'price',
        'total',
        'description',
    ];
}
