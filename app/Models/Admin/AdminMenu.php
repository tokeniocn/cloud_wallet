<?php

namespace App\Models\Admin;

use App\Models\Traits\TableName;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    use TableName;

    public static function menu()
    {
        $data = static::where('is_show', 1)
            ->orderBy('sort', 'desc')
            ->get();
        return static::normalizeMenu($data);
    }

    protected static function normalizeMenu($data, $parentId = 0)
    {
        $menu = [];
        foreach ($data as $item) {
            if ($item->parent_id == $parentId) {
                $children = static::normalizeMenu($data, $item->id);

                $menu[] = array_merge($item->toArray(), empty($children) ? [] : ['children' => $children]);
            }
        }

        return $menu;
    }
}
