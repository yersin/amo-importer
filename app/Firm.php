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
        $phone = new FirmTel();
        $phone->setConnection($this->getConnectionName());
        return $this->hasMany($phone, "firm_id", "id");
    }

    public function category()
    {
        $rubric = new FirmRubric();
        $rubric->setConnection($this->getConnectionName());
        return $this->belongsToMany($rubric, "FirmRubricRel", "firm_id", "rubric_id")
            ->where("notNeed", FirmRubric::NEED);
    }

    public function integrated($integrated = self::NOT_INTEGRATED)
    {
        return $this->join('FirmRubricRel', 'Firm.id', '=', 'FirmRubricRel.firm_id')
            ->join('FirmRubric', 'FirmRubric.rubric_id', '=', 'FirmRubricRel.rubric_id')
            ->select('Firm.*')
            ->where("Firm.isIntegrated", self::NOT_INTEGRATED)
            ->where("FirmRubric.notNeed", FirmRubric::NEED)->distinct();
    }
}
