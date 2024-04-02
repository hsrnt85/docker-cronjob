<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigMenu extends Model
{
    use HasFactory;

    protected $table = 'config_menu';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function submenus()
    {
        return $this->hasMany(ConfigSubmenu::class, 'config_menu_id');
    }
}
