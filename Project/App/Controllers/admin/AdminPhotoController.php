<?php
namespace App\Controllers\admin;

use App\Models\Photo;
use Core\QueryBuilder;

class AdminPhotoController
{
  public static function index()
  {

    $queryBuilder = new QueryBuilder();
    $photos = $queryBuilder->select(['id', 'file', 'group_id', 'user_id'])->from('photos')->fetchAll();

    return view('admin.photo.photo', data: ['photos' => $photos])->layout('admin');
  }

  public static function delete()
  {
    $id = $_POST['id'];
    $queryBuilder = new QueryBuilder();
    $query = $queryBuilder->delete()->from('photos')->where('id','=', $id)->execute();
    
    return redirect('/admin/photo');
  }


  public static function updateIndex(int $id)
  {
    $queryBuilderPhoto = new QueryBuilder();
    $photo = $queryBuilderPhoto->select(['id', 'file', 'group_id', 'user_id'])->from('photos')->where('id', '=', $id)->fetch();

    $queryBuilderGroup = new QueryBuilder();
    $group = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();

    if (!$photo) {
      return redirect('/admin/photo');
    }

    return view('admin.photo.photo_form', ['photo' => $photo,'group_list' => $group,'user_list'=> $users])->layout('admin');
  }
  public static function update()
  {
    $id = $_POST['id'];
    $file = $_POST['file'];
    $group_id = $_POST['group_id'];
    $user_id = $_POST['user_id'];

    $photo = new Photo($id, $file, $group_id, $user_id);
    $photo->update();
    
    return redirect('/admin/photo');
  }

  public static function addIndex()
  {
    $queryBuilderGroup = new QueryBuilder();
    $group = $queryBuilderGroup->select(['id', 'name'])->from('groups')->fetchAll();
    
    $queryBuilderUser = new QueryBuilder();
    $users = $queryBuilderUser->select(['id', 'email'])->from('users')->fetchAll();


    return view('admin.photo.photo_form',['group_list' => $group,'user_list' => $users])->layout('admin');
  }
  public static function add()
  {
    $file = $_POST['file'];
    $group_id = $_POST['group_id'];
    $user_id = $_POST['user_id'];

    $photo = new Photo(null, $file, $group_id, $user_id);
    $photo->createPhoto();
    
    return redirect('/admin/photo');
  }
}
