<?php
 
namespace App\Interfaces;
 
interface Model
{
    public function resolveCriteria($id);
    public function getOne($id);
    public function getList($data);
    public function getPaginatedList($data, $perPage);
    public function Create();
    public function Update($data, $id);
    public function Delete($id);
    public function mapDataModel(array $data);
}