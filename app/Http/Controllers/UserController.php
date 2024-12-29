<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserServiceInterface;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $user;
    public function __construct(UserServiceInterface $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('User/Index', [
            'users' => $this->user->list(10)->items(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $request->user()->fill($request->validated());
        DB::beginTransaction();
        try {
            $this->user->store((array)$request->all());
            DB::commit();
            return Redirect::route('users.index')->with('success', 'User created successfully.');
        }catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return Inertia::render('User/View', [
            'user' => $this->user->find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        return Inertia::render('User/Edit', [
            'user' => $this->user->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id)
    {
        DB::beginTransaction();
        try {
            $this->user->update($id, $request->all());
            DB::commit();
            return Redirect::route('users.index')->with('success', 'User update successfully.');
        }catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->user->destroy($id);
        return Redirect::route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Display a listing of the resource from soft delete data.
     */
    public function trashed()
    {
        return Inertia::render('User/Trashed', [
            'users' => $this->user->listTrashed(2)->items(),
        ]);
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore(int $user)
    {
        $this->user->restore($user);
        return Redirect::route('users.index')->with('success', 'User update successfully.');
    }

    /**
     * Permanent delete the specified resource from storage.
     */
    public function delete(int $user)
    {
        $this->user->delete($user);
        return Redirect::route('users.trashed')->with('success', 'User deleted successfully.');
    }
}
