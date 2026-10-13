<?php
declare(strict_types=1);
namespace GreatMarketrealmCartographer;
defined('ABSPATH') || exit;
/** Built-in, versioned prefab metadata. Geometry is always enforced by Scene::PREFABS. */
final class PrefabCatalog
{
    public const ITEMS = [
        'cottage'=>['name'=>'Cottage','category'=>'buildings','style'=>'marketrealm'],
        'tavern'=>['name'=>'Tavern','category'=>'buildings','style'=>'marketrealm'],
        'blacksmith'=>['name'=>'Blacksmith','category'=>'buildings','style'=>'marketrealm'],
        'tower'=>['name'=>'Watchtower','category'=>'buildings','style'=>'marketrealm'],
        'chamber'=>['name'=>'Dungeon chamber','category'=>'interiors','style'=>'stone'],
        'corridor'=>['name'=>'Stone corridor','category'=>'interiors','style'=>'stone'],
        'crypt'=>['name'=>'Crypt','category'=>'interiors','style'=>'stone'],
        'market'=>['name'=>'Market stalls','category'=>'settlements','style'=>'marketrealm'],
    ];
    public static function items(): array
    {
        $items = [];
        foreach (self::ITEMS as $id => $item) {
            if (!isset(Scene::PREFABS[$id])) continue;
            $items[$id] = $item + ['size'=>Scene::PREFABS[$id]];
        }
        return $items;
    }
}
