<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirmRubric extends Model
{
    const NEED = 0;
    const NOT_NEED = 1;
    protected $primaryKey = "rubric_id";
    public $incrementing = false;
    protected $table = "FirmRubric";
    public $timestamps = false;

    public $fillable = ["title", "groupTitle", "activity_id" , "notNeed"];
}
