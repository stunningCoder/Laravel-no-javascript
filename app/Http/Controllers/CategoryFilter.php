<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryFilter extends Controller
{
    private static $categoryState = [];

    public static function defaultState($forceDefault = false)
    {
        self::$categoryState = session()->get('categoryState');

        if (!self::$categoryState || $forceDefault) {
            foreach (Category::roots() as $parent) {

                self::$categoryState[$parent->id] = ['expand' => false, 'checked' => false];

                foreach ($parent->allChildrenIds() as $children) {
                    self::$categoryState[$children] = ['expand' => false, 'checked' => false];
                }
            }

            session()->put('categoryState', self::$categoryState);
        }


        return self::$categoryState;
    }

    public function selectCategory($categoryId)
    {
        self::defaultState();

        if (array_key_exists($categoryId, self::$categoryState)) {
            self::$categoryState[$categoryId] = ['expand' => true, 'checked' => true];
            $category = Category::whereId($categoryId)->first();

            foreach ($category->allChildrenIds() as $children) {
                self::$categoryState[$children] = ['expand' => true, 'checked' => true];
            }
            foreach ($category->parents() as $parent) {
                self::$categoryState[$parent->id]['expand'] = true;
            }

            session()->put('categoryState', self::$categoryState);
        }

        return redirect(route('category.filter'));
    }

    public function selectAll()
    {
        self::defaultState();
        foreach (self::$categoryState as &$category) {
            $category = ['expand' => true, 'checked' => true];
        }

        session()->put('categoryState', self::$categoryState);

        return redirect(route('category.filter'));
    }

    public function deselectAll()
    {
        self::defaultState();
        foreach (self::$categoryState as &$category) {
            $category = ['expand' => false, 'checked' => false];
        }

        session()->put('categoryState', self::$categoryState);

        return redirect(route('category.filter'));
    }

    public function deselectCategory($categoryId)
    {
        self::defaultState();

        if (array_key_exists($categoryId, self::$categoryState)) {
            self::$categoryState[$categoryId] = ['expand' => false, 'checked' => false];
            $category = Category::whereId($categoryId)->first();

            foreach ($category->parents() as $parent) {
                self::$categoryState[$parent->id]['checked'] = false;

                self::$categoryState[$parent->id]['expand'] = false;
                foreach ($parent->allChildrenIds() as $childId) {
                    if (self::$categoryState[$childId]['checked']) {
                        self::$categoryState[$parent->id]['expand'] = true;
                        break;
                    }
                }
            }

            foreach ($category->allChildrenIds() as $children) {
                self::$categoryState[$children] = ['expand' => false, 'checked' => false];
            }

            session()->put('categoryState', self::$categoryState);
        }

        return redirect(route('category.filter'));
    }

    public static function onlyOne($category)
    {
        self::defaultState(true);
        self::$categoryState[$category->id] = ['expand' => true, 'checked' => true];

        foreach ($category->allChildrenIds() as $children) {
            self::$categoryState[$children] = ['expand' => true, 'checked' => true];
        }

        foreach ($category->parents() as $parent) {
            self::$categoryState[$parent->id]['expand'] = true;
        }

        session()->put('categoryState', self::$categoryState);
    }
}
