<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Incoterm extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'incoterms';
    protected $fillable   = ['name'];
}