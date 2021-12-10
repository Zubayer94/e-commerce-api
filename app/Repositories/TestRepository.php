<?php

namespace App\Repositories;


use Illuminate\Http\Request;
use App\Interfaces\CrudInterface;

class TestRepository implements CrudInterface
{

    public function getAll($length, Request $request)
    {
        $Tests = Test::select('id', 'title', 'description', 'user_id', 'created_at')
            ->orderby('id', 'desc')
            ->with(['user', 'comments.user'])
            ->paginate($length);
        return $Tests;
    }

    public function create(Request $request)
    {
        $data = $request->only(['title', 'description']);
        $data['user_id'] = auth()->user()->id;
        $Test = Test::create($data);
        $Test = Test::with(['user', 'comments'])->findOrfail($Test->id);
        return $Test;
    }

    public function findById($id)
    {
        $Test = Test::with(['user', 'comments.user'])->findOrfail($id);
        return $Test;
    }

    public function update(Request $request, $id)
    {
        $Test = Test::with(['user', 'comments.user'])->findOrfail($id);
        $data = $request->only(['title', 'description']);
        $Test->fill($data)->save();
        return $Test;
    }

    public function delete($id)
    {
        $Test = Test::findOrfail($id);
        $Test->delete();
        return $Test;
    }
}
