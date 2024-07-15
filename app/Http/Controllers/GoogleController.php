<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Revolution\Google\Sheets\Facades\Sheets;

class GoogleController extends Controller {
    public function updateTableOfUsers() {
        $users = User::all();

        foreach ($users as $i => $user) {
            /*
                header columns:
                Seq,
                Name,
                Telegram link,
                Income,
                How much does he buy advertising for,
                Ready to spend on advertising,
                Advertising Purchase Platform,
                Amount of bonus
            */
            $dataSet[] = array(
                ++$i,
                $user->name,
                $this->getTgLink($user->chat_id),
                $user->income,
                $user->cost,
                $user->deposit,
                $user->platform_name,
                $user->amount_bonus
            );
        }

        Sheets::spreadsheet('1nw43hPon17RPKE1ftJOwzDOklYNWWSLhRuFCxfizqgU')->sheet('otzyvy.users')->range('A2')->update($dataSet);
    }

    public function updateTableOfReviews() {
        $reviews = Review::all();

        foreach ($reviews as $i => $review) {
            $user = User::where('chat_id', $review->chat_id)->first();
            /*
                header columns:
                Seq,
                Name,
                Telegram link,
                Blogger name,
                Review
            */
            $dataSet[] = array(
                ++$i,
                $user->name,
                $this->getTgLink($review->chat_id),
                $review->blogger_name,
                $review->text_review
            );
        }

        Sheets::spreadsheet('1nw43hPon17RPKE1ftJOwzDOklYNWWSLhRuFCxfizqgU')->sheet('otzyvy.reviews')->range('A2')->update($dataSet);
    }

    private function getTgLink($chat_id) {
        $tgChat = DB::table('telegraph_chats')->where('chat_id', $chat_id)->first();
        $tgLink = 't.me/' . explode(' ', $tgChat->name)[1];
        return $tgLink;
    }
}
