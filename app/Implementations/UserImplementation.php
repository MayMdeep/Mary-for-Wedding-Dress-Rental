<?php

namespace App\Implementations;

use App\Interfaces\Model;
use App\Models\User;

class UserImplementation implements Model
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function resolveCriteria($data = [])
    {

        $query = User::Query();

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
            $query = $query->where('name', 'like', '%' . $data['keywords'] . '%');
        }

        if (array_key_exists('email', $data)) {
            $query = $query->where('email', $data['email']);
        }
        if (array_key_exists('role_id', $data)) {
            $query = $query->where('role_id', $data['role_id']);
        }

        if (array_key_exists('phone', $data)) {
            $query = $query->where('phone', $data['phone']);
        }

        if (array_key_exists('username', $data)) {
            $query = $query->where('username', $data['username']);
        }

        if (array_key_exists('name', $data)) {
            $query = $query->where('name', $data['name']);
        }
        if (array_key_exists('id', $data)) {
            $query = $query->where('id', $data['id']);
        }

        if (array_key_exists('orderBy', $data)) {
            $query = $query->orderBy($data['orderBy'], 'DESC');
        } else {
            $query = $query->orderBy('id', 'DESC');
        }

        if (array_key_exists('year', $data)) {
            $query = $query->whereYear('created_at', $data['year']);
        }
 //$query = $query->with('role');

        return $query;
    }

    public function getOne($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    public function getList($data)
    {
        $list = $this->resolveCriteria($data)->get();
        return $list;
    }
    public function getCount($data) {
        $list = $this->resolveCriteria($data)->count();
        return $list;
    }

    public function getPaginatedList($data, $perPage)
    {
        $list = $this->resolveCriteria($data)->paginate($perPage);
        return $list;
    }

    public function Update($data = [], $id)
    {
        $this->user = $this->getOne($id);

        $this->mapDataModel($data);

        $this->user->save();

        return $this->user;
    }

    public function Create($data = [])
    {

        $this->mapDataModel($data);

        $this->user->save();

        return $this->user;
    }
    public function Delete($id)
    {
        $record = $this->getOne($id);

        $record->delete();
    }

    public function mapDataModel($data)
    {
        $attribute = [	
			'id'
            ,'name'
            ,'email'
            ,'role_id'
			,'username'
			,'password'
            ,'profile_image'
		
        ];

        foreach ($attribute as $val) {
            if (array_key_exists($val, $data)) {
                if ($val == 'password') {
                    $this->user->$val = bcrypt($data[$val]);
                }else {
                    $this->user->$val = $data[$val];
                }
            }
        }
    }
}
