<?php

namespace App\Models\User\Employee;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\User\Employee\Traits\EmployeeRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, EmployeeRelations, HasCustomRouteId;

    protected $cascadeDeletes = ['user'];

    protected $fillable = [
        'user_id',
        'joining_date',
        'alias_name',
        'father_name',
        'mother_name',
        'birth_date',
        'personal_email',
        'official_email',
        'personal_contact_no',
        'official_contact_no',
        'religion_id',
        'gender',
        'blood_group',
    ];
}
