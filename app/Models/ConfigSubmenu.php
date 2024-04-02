<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigSubmenu extends Model
{
    use HasFactory;

    protected $table = 'config_submenu';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function config_submenu($route_prefix)
    {
        $data = self::join('config_menu', 'config_menu.id', '=','config_submenu.config_menu_id')
                    ->select('config_menu.id AS module_id', 'config_menu.menu AS module_name','config_submenu.id AS submodule_id','config_submenu.submenu AS submodule_name')
                    ->where(['config_submenu.route_prefix' => $route_prefix, 'config_submenu.data_status' => 1])
                    ->first();
        return $data;
    }

    public function menu()
    {
        return $this->belongsTo(ConfigMenu::class, 'config_menu_id');
    }



}
