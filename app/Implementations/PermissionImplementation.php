<?php

namespace App\Implementations;

use App\Interfaces\Model;
use App\Models\Permission;
use App\Models\Order;

class PermissionImplementation implements Model
{
    private $permission;

    function __construct()
    {
        $this->permission = New Permission();
    }
    public function resolveCriteria($data = [])
    {
        $query = Permission::Query();

        if (array_key_exists('columns', $data)) {
            $query = $query->select($data['columns']);
        } elseif (array_key_exists('raw_columns', $data)) {
            $query = $query->selectRaw($data['raw_columns']);
        } else {
            $query = $query->select("*");
        }
        if (array_key_exists('keywords', $data)) {
            $query = $query->whereHas('allTranslations', function ($query) use ($data){
                return $query->where('value', 'like', '%'.$data['keywords'].'%');
            })->orWhere('name',  'like', '%'.$data['keywords'].'%');
        }
        if (array_key_exists('name', $data)) {
            $query = $query->where('name',  $data['name']);
        }
        if (array_key_exists('id', $data)) {
            $query = $query->where('id',  $data['id']);
        }
        if (array_key_exists('role_id', $data)) {
            $role_id = $data['role_id'];

            $query->whereIn('id', function($query) use ($role_id) {(
                $query->select('permission')
                      ->from(with(new ProductsOrdersView))->getTable())
                      ->where('id', $role_id);
            });     
        }

        if (array_key_exists('orderBy', $data)) {
            $query = $query->orderBy($data['orderBy'], 'DESC');
        }else {
            $query = $query->orderBy('id', 'DESC');
        }

        return $query;

    }

    public function getOne($id)
    {
        $permission = Permission::findOrFail($id);
        return $permission;
    }
    public function getList($data) {
        $list = $this->resolveCriteria($data)->get();
        return $list;
    }
    public function getPaginatedList($data, $perPage) {
        $list = $this->resolveCriteria($data)->paginate($perPage);
        return $list;
    }
    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->permission->save();

        return $this->permission;

    }
    public function Update($data = [], $id)
    {
        $this->permission = $this->getOne($id);

        $this->mapDataModel($data);

        $this->permission->save();

        return $this->permission;
    }
    public function Delete($id) {
        $record = $this->getOne($id);

        $record->delete();
    }

    public function mapDataModel($data)
    {
        $attribute = [
			'id'
			,'name'
			,'created_at'
			,'updated_at'
			,'deleted_at'
			,'created_by'
			,'updated_by'
			,'deleted_by'
        ];
        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
                $this->permission->$val = $data[$val];
            }
        }
    }


}
