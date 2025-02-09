<?php
namespace App\Controllers\admin;

use App\Models\User;
use App\Requests\LoginRequest;
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
    $query = $queryBuilder->delete()->from('photos')->where('id', $id)->execute();
    
    return redirect('/admin/photo');
  }

}
