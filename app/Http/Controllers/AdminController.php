<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Telegram\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {
    public function index(Request $request) {
        $reviews = DB::table('reviews')->where('is_moderated', 0)->paginate(2);
        $reviews2 = DB::table('reviews')->whereNot('is_moderated', 0)->paginate(2);
        $users = DB::table('users')->get();
        $users2 = DB::table('users')->paginate(2);
        $tgChats = DB::table('telegraph_chats')->get();
        // dd($users2);
        foreach ($tgChats as $tgChat) {
            foreach ($users as $user) {
                if ($tgChat->chat_id == $user->chat_id) {
                    $user->link = 't.me/' . explode(' ', $tgChat->name)[1];
                }
            }
        }

        foreach ($tgChats as $tgChat) {
            foreach ($users2 as $user) {
                if ($tgChat->chat_id == $user->chat_id) {
                    $user->link = 't.me/' . explode(' ', $tgChat->name)[1];
                }
            }
        }

        $adminId = array(255499895, env('ADMIN_ID'), env('ADMIN_ID_2'));
        $isAdmin = in_array($request->chat_id, $adminId);

        return view('admin', [
            'reviews' => $reviews,
            'reviews2' => $reviews2,
            'users' => $users,
            'users2' => $users2,
            'isAdmin' => $isAdmin,
            'chatId' => $request->chat_id,
            'tabNumb' => $request->tab_numb ?? 1
        ]);
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

        return redirect()->to('/admin?chat_id=' . $inputData['chat_id'])->send();
    }

    public function reject(Request $request) {
        $inputData = $request->all();
        $review = Review::where('id', $inputData['id'])->first();
        $review->is_moderated = 2;
        $review->save();

        return redirect()->to('/admin?chat_id=' . $inputData['chat_id'])->send();
    }

    public function getScreenshot(Request $request) {
        $token = env('BOT_TOKEN');
        $pathToPhoto = $this->sendRequest("https://api.telegram.org/bot$token/getFile?file_id=" . $request->id_photo);
        $link = "https://api.telegram.org/file/bot$token/" . $pathToPhoto['result']['file_path'];
        return view('iframe', ['link' => $link, 'chat_id' => $request->chat_id]);
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
