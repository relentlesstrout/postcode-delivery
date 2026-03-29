<?php

namespace App\Models;

use Database\Factories\PostcodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    /** @use HasFactory<PostcodeFactory> */
    use HasFactory;
}
