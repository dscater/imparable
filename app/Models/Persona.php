<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "paterno",
        "materno",
        "ci",
        "ci_exp",
        "dir",
        "fono",
        "fecha_nac",
        "correo",
        "fecha_registro",
        "status",
    ];

    protected $appends = ["full_name", "full_ci", "fecha_nac_t"];

    public function getFullNameAttribute()
    {
        return $this->nombre . " " . $this->paterno . ($this->materno ? " " . $this->materno : "");
    }

    public function getFullCiAttribute()
    {
        return $this->ci . " " . $this->ci_exp;
    }

    public function getFechaNacTAttribute()
    {
        if ($this->fecha_nac) {
            return date("d/m/Y", strtotime($this->fecha_nac));
        }
        return null;
    }
}
