<?php

namespace App\Models;

use App\Models\Concerns\HasPartyProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    use HasPartyProfile;

    protected $fillable = [
        'client_name',
        'contact_info',
        'address',
        'first_name',
        'second_name',
        'country',
        'province',
        'district',
        'sector',
        'cell',
        'village',
        'age',
        'gender',
        'email',
        'telephone',
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected function partyDisplayNameColumn(): string
    {
        return 'client_name';
    }
}
