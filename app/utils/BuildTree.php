<?php
declare (strict_types = 1);

namespace app\utils;

class BuildTree
{
    /**
     * 构建树形结构
     * @param array $items 原始菜单数据（已排序）
     * @param int $parentId 父级ID
     * @param string $childrenKey 子节点字段名
     * @return array
     */
    public static function build(array $items, int $parentId = 0, string $childrenKey = 'children'): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['pid'] == $parentId) {
                $children = self::build($items, (int) $item['id'], $childrenKey);
                if (! empty($children)) {
                    $item[$childrenKey] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }
}
