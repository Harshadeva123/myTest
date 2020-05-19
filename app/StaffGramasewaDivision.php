<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffGramasewaDivision extends Model
{
    protected $table = 'staff_gramasewa_devision';
    protected $primaryKey = 'idstaff_gramasewa_devision';

    public function staff(){
        return $this->belongsTo(OfficeStaff::class,'idoffice_staff');
    }

    public function gramasewa(){
        return $this->belongsTo(GramasewaDivision::class,'idgramasewa_division');
    }
}
