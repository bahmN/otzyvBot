<?php

namespace App\Telegram;

use App\Models\Cache;
use App\Models\Review;
use App\Models\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class Handler extends WebhookHandler {
    public function start() {
        if (!empty($this->message)) {
            $this->chat->deleteMessage($this->message->id())->send();
        }
        $user = User::where('chat_id', $this->chat->chat_id)->first();

        if (!$user) {
            $cache = new Cache();

            $this->chat->message(__('greeting'))->send();
            sleep(1);
            $this->chat->message(__('getUserName'))->send();

            $cache->chat_id = $this->chat->chat_id;
            $cache->action = 'getName';
            $cache->save();
        } else {
            $this->menu();
        }
    }

    public function getIncome() {
        $this->chat->message(trans_choice('getInfoAboutUser', 0))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('getIncomeButton', 0))
                            ->action('getCost')
                            ->param('value', '0-100к')
                    ])
                    ->row([
                        Button::make(trans_choice('getIncomeButton', 1))
                            ->action('getCost')
                            ->param('value', '100к-500к')
                    ])
                    ->row([
                        Button::make(trans_choice('getIncomeButton', 2))
                            ->action('getCost')
                            ->param('value', '500к-1м')
                    ])
                    ->row([
                        Button::make(trans_choice('getIncomeButton', 3))
                            ->action('getCost')
                            ->param('value', '1м-3м')
                    ])
                    ->row([
                        Button::make(trans_choice('getIncomeButton', 4))
                            ->action('getCost')
                            ->param('value', '3м+')
                    ])
            )
            ->send();
    }

    public function getCost() {
        $user = User::where('chat_id', $this->chat->chat_id)->first();
        $user->income = $this->data->get('value');
        $user->save();

        $this->chat->edit($this->callbackQuery->message()->id())->message(trans_choice('getInfoAboutUser', 1))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('getCostButton', 0))
                            ->action('getDeposit')
                            ->param('value', '0-50к')
                    ])
                    ->row([
                        Button::make(trans_choice('getCostButton', 1))
                            ->action('getDeposit')
                            ->param('value', '50к-100к')
                    ])
                    ->row([
                        Button::make(trans_choice('getCostButton', 2))
                            ->action('getDeposit')
                            ->param('value', '100к- 250к')
                    ])
                    ->row([
                        Button::make(trans_choice('getCostButton', 3))
                            ->action('getDeposit')
                            ->param('value', '250к+')
                    ])
                    ->row([
                        Button::make(trans_choice('getCostButton', 4))
                            ->action('getDeposit')
                            ->param('value', 'Не закупаю рекламу')
                    ])
            )
            ->send();
    }

    public function getDeposit() {
        $user = User::where('chat_id', $this->chat->chat_id)->first();
        $user->cost = $this->data->get('value');
        $user->save();

        $this->chat->edit($this->callbackQuery->message()->id())->message(trans_choice('getInfoAboutUser', 2))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('getDepositButton', 0))
                            ->action('getPlatformName')
                            ->param('value', '0-50к')
                    ])
                    ->row([
                        Button::make(trans_choice('getDepositButton', 1))
                            ->action('getPlatformName')
                            ->param('value', '50к-100к')
                    ])
                    ->row([
                        Button::make(trans_choice('getDepositButton', 2))
                            ->action('getPlatformName')
                            ->param('value', '100к- 250к')
                    ])
                    ->row([
                        Button::make(trans_choice('getDepositButton', 3))
                            ->action('getPlatformName')
                            ->param('value', '250к+')
                    ])
                    ->row([
                        Button::make(trans_choice('getDepositButton', 4))
                            ->action('getPlatformName')
                            ->param('value', '0')
                    ])
            )
            ->send();
    }

    public function getPlatformName() {
        $user = User::where('chat_id', $this->chat->chat_id)->first();
        $user->deposit = $this->data->get('value');
        $user->save();

        $this->chat->edit($this->callbackQuery->message()->id())->message(trans_choice('getInfoAboutUser', 3))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('getPlatformNameButton', 0))
                            ->action('successSignUp')
                            ->param('value', 'Instagram (Сторис)')
                    ])
                    ->row([
                        Button::make(trans_choice('getPlatformNameButton', 1))
                            ->action('successSignUp')
                            ->param('value', 'Instagram (Reels)')
                    ])
                    ->row([
                        Button::make(trans_choice('getPlatformNameButton', 2))
                            ->action('successSignUp')
                            ->param('value', 'YouTube (Подкаст)')
                    ])
                    ->row([
                        Button::make(trans_choice('getPlatformNameButton', 3))
                            ->action('successSignUp')
                            ->param('value', 'Telegram (Эфир)')
                    ])
                    ->row([
                        Button::make(trans_choice('getPlatformNameButton', 4))
                            ->action('successSignUp')
                            ->param('value', 'Telegram (Пост)')
                    ])
            )
            ->send();
    }

    public function successSignUp() {
        $user = User::where('chat_id', $this->chat->chat_id)->first();
        $user->platform_name = $this->data->get('value');
        $user->amount_bonus = 5;
        $user->save();

        $cache = Cache::where('chat_id', $this->chat->chat_id)->first();
        $cache->action = null;
        $cache->save();

        $this->chat->edit($this->callbackQuery->message()->id())->message(__('successSignUp'))->send();
        sleep(3);
        $this->menu();
    }

    public function menu() {
        $cache = Cache::where('chat_id', $this->chat->chat_id)->first();
        $cache->action = null;
        $cache->blogger_name = null;
        $cache->save();

        $this->chat->message(__('menu'))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('menuButton', 0))
                            ->action('addReviewStart')
                    ])
                    ->row([
                        Button::make(trans_choice('menuButton', 1))
                            ->action('statusAccount')
                    ])
                    ->row([
                        Button::make(trans_choice('menuButton', 2))
                            ->action('searchBlogger')
                    ])
            )
            ->send();
    }

    public function addReviewStart() {
        $this->chat->message(trans_choice('addReview', 0))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(__('addReviewButton'))
                            ->action('addReviewGetBloggerName')
                    ])
            )
            ->send();
    }

    public function addReviewGetBloggerName() {
        $cache = Cache::where('chat_id', $this->chat->chat_id)->first();
        $cache->action = 'getBloggerName';
        $cache->save();

        $this->chat->message(trans_choice('addReview', 1))->send();
    }

    public function addReviewGetResponseToQuestions() {
        $cache = Cache::where('chat_id', $this->chat->chat_id)->first();
        $cache->action = 'getResponseToQuestions';
        $cache->save();

        $this->chat->message(trans_choice('addReview', 2))->send();
    }

    public function addReviewSuccess() {
        $user = User::where('chat_id', $this->chat->chat_id)->first();
        $user->amount_bonus += 5;
        $user->save();

        $this->chat->message(trans_choice('addReview', 3))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('menuButton', 3))
                            ->action('menu')
                    ])
            )
            ->send();
    }

    public function statusAccount() {
        $user = User::where('chat_id', $this->chat->chat_id)->first();

        if ($user->amount_bonus >= 1 && $user->amount_bonus <= 55) {
            $statusData = array(
                'status' => 'Начинающий закупщик',
                'discount' => 1
            );
        } else if ($user->amount_bonus >= 56 && $user->amount_bonus <= 555) {
            $statusData = array(
                'status' => 'Средний закупщик',
                'discount' => 3
            );
        } else if ($user->amount_bonus >= 556 && $user->amount_bonus <= 1555) {
            $statusData = array(
                'status' => 'Профессиональный закупщик',
                'discount' => 5
            );
        }

        $this->chat->edit($this->callbackQuery->message()->id())
            ->message(__(
                'statusAccount',
                [
                    'userName' => $user->name,
                    'amountBonus' => $user->amount_bonus,
                    'status' => $statusData['status'],
                    'discount' => $statusData['discount']
                ]
            ))
            ->keyboard(
                Keyboard::make()
                    ->row([
                        Button::make(trans_choice('menuButton', 3))
                            ->action('start')
                    ])
            )
            ->send();
    }

    public function searchBlogger() {
        $cache = Cache::where('chat_id', $this->chat->chat_id)->first();
        $cache->action = 'searchBlogger';
        $cache->save();

        $this->chat->edit($this->callbackQuery->message()->id())
            ->message(trans_choice('searchBlogger', 0))
            ->send();
    }

    protected function handleChatMessage(Stringable $text): void {
        $cache = Cache::where('chat_id', $this->chat->chat_id)->first();

        if ($cache->action == 'getName') {
            $this->chat->deleteMessage($this->message->id())->send();

            $user = new User();
            $user->chat_id = $this->chat->chat_id;
            $user->name = $text;
            $user->save();

            $this->getIncome();
        } else if ($cache->action == 'getBloggerName') {
            $this->chat->deleteMessage($this->message->id())->send();

            $review = new Review();
            $review->chat_id = $this->chat->chat_id;
            $review->blogger_name = $text;
            $review->save();

            $cache->blogger_name = $text;
            $cache->save();

            $this->addReviewGetResponseToQuestions();
        } else if ($cache->action == 'getResponseToQuestions') {
            $this->chat->deleteMessage($this->message->id())->send();

            $review = Review::where('chat_id', $this->chat->chat_id)
                ->where('blogger_name', $cache->blogger_name)
                ->first();
            $review->text_review = $text;
            $review->save();

            $this->addReviewSuccess();
        } else if ($cache->action == 'searchBlogger') {
            $this->chat->deleteMessage($this->message->id())->send();

            $reviews = DB::table('reviews')->where('blogger_name', $text)->get();
            $string = '';
            if (!empty($reviews->toArray())) {
                foreach ($reviews as $review) {
                    $string .= "*$review->blogger_name*\n$review->text_review\n\n";
                }
            } else {
                $string = trans_choice('searchBlogger', 1);
            }
            $this->chat->message($string)
                ->keyboard(
                    Keyboard::make()
                        ->row([
                            Button::make(trans_choice('menuButton', 3))
                                ->action('start')
                        ])
                )
                ->send();
        }
    }
}
