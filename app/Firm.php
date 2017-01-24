<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    const NOT_INTEGRATED = 0;
    const INTEGRATED = 1;
    protected $table = "Firm";
    public $incrementing = false;
    public $timestamps = false;
    public function phones()
    {
        return $this->hasMany('App\FirmTel', "firm_id", "id");
    }

    public function category()
    {
        return $this->belongsToMany('App\FirmRubric', "FirmRubricRel", "firm_id", "rubric_id")
            ->where("notNeed", FirmRubric::NEED);
    }
}
