<?php

namespace App\Implementations;

use Carbon\Carbon;
use App\Models\Admin;
use App\Interfaces\Model;
use App\Models\BlockedAdmin;
use Illuminate\Support\Facades\DB;
use App\Models\Views\TopVendorsView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminImplementation implements Model
{
    private $admin;

    function __construct()
    {
        $this->admin  = new Admin();
    }

    public function resolveCriteria($data = [])
    {

        $query = Admin::Query();

        if (array_key_exists('columns', $data)) {
            $query = $query->select($data['columns']);
        } elseif (array_key_exists('raw_columns', $data)) {
            $query = $query->selectRaw($data['raw_columns']);
        } else {
            $query = $query->select("*");
        }
        if (array_key_exists('id', $data)) {
            $query = $query->where('id', $data['id']);
        }
        if (array_key_exists('keywords', $data)) {
            $query = $query->where('name',  'like', '%' . $data['keywords'] . '%');
        }


        if (array_key_exists('email', $data)) {
            $query = $query->where('email',  $data['email']);
        }
        if (array_key_exists('role_id', $data)) {
            $query = $query->where('role_id',  $data['role_id']);
        }

        if (array_key_exists('username', $data)) {
            $query = $query->where('username',  $data['username']);
        }

        if (array_key_exists('name', $data)) {
            $query = $query->where('name',  $data['name']);
        }

        if (array_key_exists('id', $data)) {
            $query = $query->where('id',  $data['id']);
        }
       

        if (array_key_exists('orderBy', $data)) {
            $query = $query->orderBy($data['orderBy'], 'DESC');
        } else {
            $query = $query->orderBy('id', 'DESC');
        }
     
        return $query;
    }


    public function getOne($id)
    {
        $admin = Admin::findOrFail($id);
        return $admin;
    }

    public function getList($data)
    {
        $list = $this->resolveCriteria($data)->get();
        return $list;
    }
    

    public function getPaginatedList($data, $perPage)
    {
        $list = $this->resolveCriteria($data)->paginate($perPage);
        return $list;
    }

    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->admin->save();

        return $this->admin;
    }

    public function Update($data = [], $id)
    {
        $this->admin = $this->getOne($id);

        $this->mapDataModel($data);

        $this->admin->save();

        return $this->admin;
    }

    public function Delete($id)
    {
        $record = $this->getOne($id);

        $record->delete();
    }

    public function mapDataModel($data)
    {
        $attribute = [
            'id',
            'name',
            'username', 
            'password', 
            'email', 'photo', 'role_id',  'last_login', 'active', 'phone_code',
               'created_at', 'updated_at', 'deleted_at', 'created_by',
        ];

        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
                if ($val == 'password') {
                    $this->admin->$val = bcrypt($data[$val]);
                } else {
                    $this->admin->$val = $data[$val];
                }
            }
        }
    }
}
