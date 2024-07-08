<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Telegram\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {
    public function index() {
        $reviews = DB::table('reviews')->where('is_moderated', 0)->simplePaginate(3);
        $users = DB::table('users')->get();
        return view('admin', ['reviews' => $reviews, 'users' => $users]);
    }


    public function approve(Request $request) {
        $inputData = $request->all();
        $review = Review::where('id', $inputData['id'])->first();
        $review->is_moderated = 1;
        $review->save();

        $user = User::where('chat_id', $inputData['chat_id'])->first();
        $user->amount_bonus += 45;
        $user->save();

        $tHandler = new Handler();
        $tHandler->moderated($inputData['chat_id']);

        return redirect()->route('admin');
    }

    public function reject(Request $request) {
        $inputData = $request->all();
        $review = Review::where('id', $inputData['id'])->first();
        $review->is_moderated = 2;
        $review->save();

        return redirect()->route('admin');
    }
}
