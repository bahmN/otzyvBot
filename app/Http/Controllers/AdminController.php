<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Telegram\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {
    public function index(Request $request) {
        $reviews = DB::table('reviews')->where('is_moderated', 0)->simplePaginate(3);
        $users = DB::table('users')->get();

        $adminId = array(255499895, 831429656);
        $isAdmin = in_array($request->chat_id, $adminId);
        return view('admin', ['reviews' => $reviews, 'users' => $users, 'isAdmin' => $isAdmin, 'chatId' => $request->chat_id]);
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

    public function getScreenshot(Request $request) {
        $pathToPhoto = $this->sendRequest('https://api.telegram.org/bot7474649392:AAHdDwamhU_AmUl0kZPZsKfrRmmgc5BquZg/getFile?file_id=' . $request->id_photo);
        $link = 'https://api.telegram.org/file/bot7474649392:AAHdDwamhU_AmUl0kZPZsKfrRmmgc5BquZg/' . $pathToPhoto['result']['file_path'];

        return redirect()->to($link)->send();
    }

    public function sendRequest($url) {
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
