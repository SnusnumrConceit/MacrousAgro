<?php

use Illuminate\Database\Seeder;
use \Kodeine\Acl\Models\Eloquent\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** права на статьи */
        $articlePermissionParent = Permission::updateOrCreate(['name' => 'articles'], [
            'name'        => 'articles',
            'description' => 'статьи',
            'slug'        => 'articles'
        ]);

        Permission::updateOrCreate(['name' => 'articles_create'], [
            'name'        => 'articles_create',
            'description' => 'добавление статей',
            'slug'        => 'articles_create',
            'inherit_id'  => $articlePermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'articles_update'], [
            'name'        => 'articles_update',
            'description' => 'редактирование статей',
            'slug'        => 'articles_update',
            'inherit_id'  => $articlePermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'articles_delete'], [
            'name'        => 'articles_delete',
            'description' => 'удаление статей',
            'slug'        => 'articles_delete',
            'inherit_id'  => $articlePermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'articles_view'], [
            'name'        => 'articles_view',
            'description' => 'просмотр статей',
            'slug'        => 'articles_view',
            'inherit_id'  => $articlePermissionParent->id
        ]);


        /** права на категории */
        $categoryPermissionParent = Permission::updateOrCreate(['name' => 'categories'], [
            'name'        => 'categories',
            'description' => 'категории',
            'slug'        => 'categories'
        ]);

        Permission::updateOrCreate(['name' => 'categories_create'], [
            'name'        => 'categories_create',
            'description' => 'добавление категорий',
            'slug'        => 'categories_create',
            'inherit_id'  => $categoryPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'categories_update'], [
            'name'        => 'categories_update',
            'description' => 'редактирование категорий',
            'slug'        => 'categories_update',
            'inherit_id'  => $categoryPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'categories_delete'], [
            'name'        => 'categories_delete',
            'description' => 'удаление категорий',
            'slug'        => 'categories_delete',
            'inherit_id'  => $categoryPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'categories_view'], [
            'name'        => 'categories_view',
            'description' => 'просмотр категорий',
            'slug'        => 'categories_view',
            'inherit_id'  => $categoryPermissionParent->id
        ]);


        /** права на заказы */
        $orderPermissionParent = Permission::updateOrCreate(['name' => 'orders'], [
            'name'        => 'orders',
            'description' => 'заказы',
            'slug'        => 'orders'
        ]);

        Permission::updateOrCreate(['name' => 'orders_create'], [
            'name'        => 'orders_create',
            'description' => 'добавление заказов',
            'slug'        => 'orders_create',
            'inherit_id'  => $orderPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'orders_update'], [
            'name'        => 'orders_update',
            'description' => 'редактирование заказов',
            'slug'        => 'orders_update',
            'inherit_id'  => $orderPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'orders_delete'], [
            'name'        => 'orders_delete',
            'description' => 'удаление заказов',
            'slug'        => 'orders_delete',
            'inherit_id'  => $orderPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'orders_view'], [
            'name'        => 'orders_view',
            'description' => 'просмотр заказов',
            'slug'        => 'orders_view',
            'inherit_id'  => $orderPermissionParent->id
        ]);


        /** права на товары */
        $productPermissionParent = Permission::updateOrCreate(['name' => 'products'], [
            'name'        => 'products',
            'description' => 'товары',
            'slug'        => 'products'
        ]);

        Permission::updateOrCreate(['name' => 'products_create'], [
            'name'        => 'products_create',
            'description' => 'добавление товаров',
            'slug'        => 'products_create',
            'inherit_id'  => $productPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'products_update'], [
            'name'        => 'products_update',
            'description' => 'редактирование товаров',
            'slug'        => 'products_update',
            'inherit_id'  => $productPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'products_delete'], [
            'name'        => 'products_delete',
            'description' => 'удаление товаров',
            'slug'        => 'products_delete',
            'inherit_id'  => $productPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'products_view'], [
            'name'        => 'products_view',
            'description' => 'просмотр товаров',
            'slug'        => 'products_view',
            'inherit_id'  => $productPermissionParent->id
        ]);

        /** права на роли */
        $rolePermissionParent = Permission::updateOrCreate(['name' => 'roles'], [
            'name'        => 'roles',
            'description' => 'роли',
            'slug'        => 'roles'
        ]);

        Permission::updateOrCreate(['name' => 'роли'], [
            'name'        => 'roles_create',
            'description' => 'добавление ролей',
            'slug'        => 'roles_create',
            'inherit_id'  => $rolePermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'roles_update'], [
            'name'        => 'roles_update',
            'description' => 'редактирование ролей',
            'slug'        => 'roles_update',
            'inherit_id'  => $rolePermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'roles_delete'], [
            'name'        => 'roles_delete',
            'description' => 'удаление ролей',
            'slug'        => 'roles_delete',
            'inherit_id'  => $rolePermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'roles_view'], [
            'name'        => 'roles_view',
            'description' => 'просмотр роли',
            'slug'        => 'roles_view',
            'inherit_id'  => $rolePermissionParent->id
        ]);


        /** права на пользователей */
        $userPermissionParent = Permission::updateOrCreate(['name' => 'users'], [
            'name'        => 'users',
            'description' => 'пользователи',
            'slug'        => 'users'
        ]);

        Permission::updateOrCreate(['name' => 'users_create'], [
            'name'        => 'users_create',
            'description' => 'добавление пользователей',
            'slug'        => 'users_create',
            'inherit_id'  => $userPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'users_update'], [
            'name'        => 'users_update',
            'description' => 'редактирование пользователей',
            'slug'        => 'users_update',
            'inherit_id'  => $userPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'users_delete'], [
            'name'        => 'users_delete',
            'description' => 'удаление пользователей',
            'slug'        => 'users_delete',
            'inherit_id'  => $userPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'users_view'], [
            'name'        => 'users_view',
            'description' => 'просмотр пользователей',
            'slug'        => 'users_view',
            'inherit_id'  => $userPermissionParent->id
        ]);


        /** права на фотографии */
        $photoPermissionParent = Permission::updateOrCreate(['name' => 'photos'], [
            'name'        => 'photos',
            'description' => 'фотографии',
            'slug'        => 'photos'
        ]);

        Permission::updateOrCreate(['name' => 'photos_create'], [
            'name'        => 'photos_create',
            'description' => 'добавление фотографий',
            'slug'        => 'photos_create',
            'inherit_id'  => $photoPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'photos_update'], [
            'name'        => 'photos_update',
            'description' => 'редактирование фотографий',
            'slug'        => 'photos_update',
            'inherit_id'  => $photoPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'photos_delete'], [
            'name'        => 'photos_delete',
            'description' => 'удаление фотографий',
            'slug'        => 'photos_delete',
            'inherit_id'  => $photoPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'photos_view'], [
            'name'        => 'photos_view',
            'description' => 'просмотр фотографий',
            'slug'        => 'photos_view',
            'inherit_id'  => $photoPermissionParent->id
        ]);


        /** права на видео */
        $videoPermissionParent = Permission::updateOrCreate(['name' => 'videos'], [
            'name'        => 'videos',
            'description' => 'видео',
            'slug'        => 'videos'
        ]);

        Permission::updateOrCreate(['name' => 'videos_create'], [
            'name'        => 'videos_create',
            'description' => 'добавление видео',
            'slug'        => 'videos_create',
            'inherit_id'  => $videoPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'videos_update'], [
            'name'        => 'videos_update',
            'description' => 'редактирование видео',
            'slug'        => 'videos_update',
            'inherit_id'  => $videoPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'videos_delete'], [
            'name'        => 'videos_delete',
            'description' => 'удаление видео',
            'slug'        => 'videos_delete',
            'inherit_id'  => $videoPermissionParent->id
        ]);

        Permission::updateOrCreate(['name' => 'videos_view'], [
            'name'        => 'videos_view',
            'description' => 'просмотр видео',
            'slug'        => 'videos_view',
            'inherit_id'  => $videoPermissionParent->id
        ]);


    }
}
