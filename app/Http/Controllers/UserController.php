<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Endpoints de usuário",
 * )
 */

class UserController extends Controller
{
	public function getSearch(Request $request) {
		return (new \App\Models\User)->search($request->all());
	}

	public function getFind($id) {
		return \App\Models\User::find($id);
	}

	public function postSave(Request $request) {
		return (new \App\Models\User)->store($request->all());
	}

	public function postDelete($id) {
		return \App\Models\User::find($id)->remove();
	}
}