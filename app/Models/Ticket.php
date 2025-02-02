<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $collection = 'tickets';

    protected $fillable = ['title', 'description', 'status', 'user_id'];

    protected $primaryKey = '_id';


    public static function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'in:open,in_progress,closed',
        ];
    }
}
