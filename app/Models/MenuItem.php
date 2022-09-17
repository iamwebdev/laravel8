<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
	public static function recursive() {
		$allMenuItems = MenuItem::all();
		$parentMenu = $allMenuItems->whereNull('parent_id');
		self::hierarchyGenerator($parentMenu, $allMenuItems);
		return $parentMenu;
	}

	private static function hierarchyGenerator($parentMenu, $allMenuItems) {
		foreach ($parentMenu as $menu) {
			$menu->children = $allMenuItems->where('parent_id', $menu->id)->values();
			if ($menu->children->isNotEmpty()) {
				self::hierarchyGenerator($menu->children, $allMenuItems);
			}
		}
	}
}
