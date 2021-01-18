<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getIndex(){
        $return = $this->join('companies as company','users.id','company.user_id')->select([
            \DB::raw('company.id as company_id'),
            'users.name',
            'users.email',
            'company.cnpj',
            'company.employees_number',
            'company.industry',
            \DB::raw("'company' as type")
        ])->where('users.id',$this->id)->get();
        \Log::error($return);
        if(count($return) == 0){
            $return = $this->join('persons as person','users.id','person.user_id')->select([
                \DB::raw('person.id as person_id'),
                'users.name',
                'users.email',
                'person.cpf',
                'person.educational_level',
                'person.occupation_area',
                'person.number',
                \DB::raw("'person' as type")
            ])->where('users.id',$this->id)->get();
        }
        return $return;
    }
}
