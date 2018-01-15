<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    public function __construce() {
      $this->middleware('auth', ['except' => ['show']]);
    }
    //显示用户详情
    public function show(User $user) {
      return view('users.show', compact('user'));
    }

    //显示编辑用户页面
    public function edit(User $user) {
      $this->authorize('update', $user);
      return view('users.edit', compact('user'));
    }

    //保存用户编辑信息
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user) {
        $this->authorize('update', $user);
        $data = $request->all();
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }


}
