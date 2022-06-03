<?php

namespace App\Http\Controllers;
use App\Telegram;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use App\User;
use App\Product;
use App\Shipping;
use App\Category;
use App\Offer;
use App\Purchase;
use App\Traits\Uuids;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Cart\NewItemRequest;
use App\Http\Requests\Cart\MakePurchasesRequest;
use App\Marketplace\Cart;
use DB;
use App\Marketplace\Encryption\Cipher;
use App\Marketplace\Encryption\Keypair;
use App\Marketplace\Utility\Mnemonic;
use Defuse\Crypto\Crypto;
use Telegram\Bot\Keyboard\Keyboard;

class TelegramController extends Controller {

    public function __construct() {
       
//        dd(config('telegram_key'));
//        dd(env('TELEGRAM_BOT_TOKEN'));
        
        $this->telegram = new Api(config('telegram_key'));
//        $this->telegram = new Api('1195408851:AAE-EU48VI2pexts22M9zXu3UbdQv4nFZ1c');
//       Log::error( $this->telegram);
    }

    public function getUpdate($offset = null) {
   
        try {
            $this->telegram->removeWebhook();
            if ($offset == null) {
                $responses = $this->telegram->getUpdates();
//                      Log::error($responses);
//       
//                      Log::error(config('telegram_key'));
//         die;
            } else {
                $responses = $this->telegram->getUpdates(['offset' => $offset]);
            }
              Log::error($responses);
            $this->handelUpdate($responses);
            exit();
        } catch (TelegramResponseException $e) {
            
            $errorData = $e->getResponseData();
            if ($errorData['ok'] === false) {
                $this->telegram->sendMessage([
                    'chat_id' => 998130354,
                    'text' => 'There was an error for a user. ' . $errorData['error_code'] . ' ' . $errorData['description'],
                ]);
            }
        }
    }

    public function offsetUpdate($offset) {
        $responses = $this->telegram->getUpdates(['offset' => $offset]);
        return;
    }

    public function handelCommands($text) {

    }

    public function handelUpdate($responses) {
//        Log::error($responses);
//         $this->telegram->sendMessage([
//                                    'chat_id' => $chat_id,
//                                    'parse_mode' => 'HTML',
//                                    'text' => 'Hello there'
//                                ]);
        if ($responses != null) {
            foreach ($responses as $response) {
                $offset = $response['update_id'] + 1;
                if (isset($response['message'])) {
                    $chat_id = $response['message']['from']['id'];
                    $text = $response['message']['text'];
                    if (isset($response['message']['from']['username'])) {
                        $from = $response['message']['from']['username'];
                        $user = User::where('telegram_username', $from)->first();
                        if ($user != NULL) {
                            if ($text == '/help') {
                                $this->telegram->sendMessage([
                                    'chat_id' => $chat_id,
                                    'parse_mode' => 'HTML',
                                    'text' => 'Hey  <strong>' . $from . '</strong>' . PHP_EOL . 'Clothes off you\'re on Rolling Market!</a>'
                                ]);
                                $this->keyword($chat_id);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '/start') {
                                $this->telegram->sendMessage([
                                    'chat_id' => $chat_id,
                                    'parse_mode' => 'HTML',
                                    'text' => 'Hey  <strong>' . $from . '</strong>' . PHP_EOL . 'Clothes off you\'re on Rolling Market!</a>'
                                ]);
                                $this->keyword($chat_id);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '🛒Cart') {
                                $this->getCart($chat_id, $from);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '📦Products') {
                                $this->getCategory($chat_id);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '🏠Home') {
                                $this->getCategory($chat_id);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '🛍️Orders') {
                                $this->purchases($chat_id, $from);
                                $this->offsetUpdate($offset);
                                exit();
                            } elseif ($text == '💼Checkout') {
                                $this->getShiping($chat_id, $from);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '🔑PGP') {
                                $this->sendPGP($chat_id);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif ($text == '🔑🙋Help/Bugs🐛') {
                                $this->help($chat_id, $from);
                                $this->offsetUpdate($offset);
                                continue;
                            } elseif (isset($response['message']['reply_to_message'])) {
                                if ($response['message']['reply_to_message']['text'] == "Add your Address:") {
                                    $this->checkoutCall($chat_id, $from, $response['message']['text']);
                                    $this->offsetUpdate($offset);
                                    continue;
                                }
                                continue;
                            } else {
                                $msg = "Invalid option please choose valid option from Keyword";
                                $this->keyword($chat_id, $msg);
                                $this->offsetUpdate($offset);
                                continue;
                            }
                        } else {
                            if ($text == 'Create New User') {
                                $this->newUserCreate($chat_id, $from);
                                $this->offsetUpdate($offset);
                                continue;
                            } else {
                                $msg = "User not registered! Do you want to create new Or" . PHP_EOL . "If you are already register with R.M please login and setup Your Telegram access";
                                $keyboard = [
                                    ['Create New User'],
                                ];
                                $reply_markup = Keyboard::make([
                                    'keyboard' => $keyboard,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => true
                                ]);

                                $response = $this->telegram->sendMessage([
                                    'chat_id' => $chat_id,
                                    'text' => $msg,
                                    'reply_markup' => $reply_markup
                                ]);

                                $this->offsetUpdate($offset);
                                continue;
                            }
                        }
                    } else {
                        $text = 'Hello ' . $response['message']['from']['first_name'] . PHP_EOL;
                        $text .= 'Hey, clothes off your are on rolling market '. PHP_EOL;
                        $text .= 'Your Telegram Username is not configured ' . PHP_EOL;
                        $text .= 'Please go to setting and update your username' . PHP_EOL;
                        $response = $this->telegram->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $text
                        ]);
                        $this->offsetUpdate($offset);
                        continue;
                    }
                } elseif (isset($response['callback_query'])) {
                    $chat_id = $response['callback_query']['from']['id'];
                    if (isset($response['callback_query']['from']['username'])) {

                        $from = $response['callback_query']['from']['username'];
                        $callback_text = $response['callback_query']['data'];

                        if (preg_match("/[\s]*(categ__)/i", $callback_text)) {
                            $cat_id = str_replace("categ__", '', $callback_text);
                            $this->getSubCategory($cat_id, $chat_id);
                            $this->offsetUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(produ__)/i", $callback_text)) {
                            $product_id = str_replace("produ__", '', $callback_text);
                            $this->getSingleProduct($chat_id, $product_id);
                            $this->offsetUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(add_cart__)/i", $callback_text)) {
                            $product_id = str_replace("add_cart__", '', $callback_text);
                            $this->addToCart($product_id, $chat_id, $from);
                            $this->getUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(remove_cart__)/i", $callback_text)) {
                            $cart_item_id = str_replace("remove_cart__", '', $callback_text);
                            $this->removeCart($cart_item_id, $chat_id, $from);
                            $this->offsetUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(purchase__)/i", $callback_text)) {
                            $purchase_id = str_replace("purchase__", '', $callback_text);
                            $this->singlePurchase($purchase_id, $chat_id, $from);
                            $this->offsetUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(shipment__)/i", $callback_text)) {
                            $shipment_id = str_replace("shipment__", '', $callback_text);
                            $this->askAddress($chat_id, $from, $shipment_id);
                            $this->offsetUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(shipmentq__)/i", $callback_text)) {
                            $string = str_replace("shipmentq__", '', $callback_text);
                            $shipment_id = str_replace("shipmentq__", '', $callback_text);
                            $this->getShipmentLoop($chat_id, $from, $shipment_id);
                            $this->offsetUpdate($offset);
                            continue;
                        } elseif (preg_match("/[\s]*(final_checkout__)/i", $callback_text)) {
                            $this->checkout($chat_id, $from);
                            $this->offsetUpdate($offset);
                            continue;
                        }
                    } else {
                        $text = 'Hello ' . $response['message']['from']['first_name'] . PHP_EOL;
                        $text .= 'Hey, clothes off, you are on Rolling Market!' . PHP_EOL;
                        $text .= 'Your Telegram Username is not configured ' . PHP_EOL;
                        $text .= 'Please go to setting and update your username' . PHP_EOL;
                        $response = $this->telegram->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $text
                        ]);
                        $this->offsetUpdate($offset);
                        continue;
                    }
                }
            }
        }
    }

    public function newUserCreate($chat_id, $from) {
        $referred_by = null;
        $keyPair = new Keypair();
        $privateKey = $keyPair->getPrivateKey();
        $publicKey = $keyPair->getPublicKey();
        $password = strtoupper(str_random(9));
        $encryptedPrivateKey = Crypto::encryptWithPassword($privateKey, $password);
        $user = new User();
        $user->username = $from;
        $user->telegram_username = $from;
        $user->password = bcrypt($password);
        $user->mnemonic = bcrypt(hash('sha256', $password));
        $user->referral_code = strtoupper(str_random(6));
        $user->msg_public_key = encrypt($publicKey);
        $user->msg_private_key = $encryptedPrivateKey;
        $user->referred_by = optional($referred_by)->id;
        $user->save();
        $response = $this->telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Hello ' . $from . ' You have been registred with us ' . PHP_EOL . 'Your Passord is :' . $password
        ]);
        $this->keyword($chat_id);
        return;
    }





    public function keyword($chat_id, $msg = "Please reply from keyword") {
        try {
            $keyboard = [
                ['📦Products'],
                ['💳Payments', '🛍️Orders'],
                ['🛒Cart', '🙋Help/Bugs🐛', '🔑PGP'],
            ];
            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $msg,
                'reply_markup' => $reply_markup
            ]);
        } catch (TelegramResponseException $e) {

            $errorData = $e->getResponseData();

            if ($errorData['ok'] === false) {
	            $this->telegram->sendMessage([
                    'chat_id' => 998130354,
                    'text' => 'There was an error for a user. ' . $errorData['error_code'] . ' ' . $errorData['description'],
                ]);
            }
        }
    }

    public function checkOutKeyword($chat_id, $msg = 'Please reply from keyword') {
        try {
            $keyboard = [
                ['💼Checkout'],
                ['🧾Payments', '🛍️Orders'],
                ['💬Chat', '🏠Home'],
            ];
//        $reply_markup = $this->telegram->replyKeyboardMarkup([
//            'keyboard' => $keyboard,
//            'resize_keyboard' => true,
//            'one_time_keyboard' => true
//        ]);
            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);
            $response = $this->telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $msg,
                'reply_markup' => $reply_markup
            ]);
            return;
        } catch (TelegramResponseException $e) {

            $errorData = $e->getResponseData();

            if ($errorData['ok'] === false) {
                $this->telegram->sendMessage([
                    'chat_id' => 998130354,
                    'text' => 'There was an error for a user. ' . $errorData['error_code'] . ' ' . $errorData['description'],
                ]);
            }
        }
    }

//    public function getCategory($chat_id = 998130354, $parent_category = null) {
    public function getCategory($chat_id, $parent_category = '') {
        if ($parent_category == '') {
            $categorys = Category::roots();
        } else {
            $categorys = $parent_category;
        }

        $catogery_data = array();
        $singal_cat = '';
        foreach ($categorys as $catogery) {
            if ($catogery->getNumProductsAttribute() > 0) {
                $catogery_data_value['callback_data'] = 'categ__' . $catogery->id;
                $catogery_data_value['text'] = $catogery->name . '(' . $catogery->getNumProductsAttribute() . ')';
                $catogery_data[] = $catogery_data_value;
                $singal_cat = $catogery;
            }
        }
        if (count($catogery_data) > 1) {
            $msg = "Select Catogery 🔡";
            $this->inlineKeyword($chat_id, $catogery_data, $msg);
            $msg = "Or select Option from keyword";
//            $this->keyword($chat_id, $msg);
            return;
        } else {
            $this->getCatProducts($chat_id, $singal_cat->childProducts());
            return;
        }
        return;
    }

    public function inlineKeyword($chat_id, $keyword_data, $msg = '') {

        try {

            if (empty($msg)) {
                $msg = "Select from list";
            }
            $inline_keyboard = array();
            $row = 0;
            $prev_value = '';
            foreach ($keyword_data as $value) {
                if (isset($prev_value['text']) && strlen($prev_value['text']) < 10 && strlen($value['text']) < 10) {
                    $inline_keyboard[$row - 1][] = $value;
                } else {
                    $inline_keyboard[$row][] = $value;
                }
                $prev_value = $value;
                $row++;
            }

//        $reply_markup = Keyboard::make()->inline()->row($inline_keyboard);
//        $inline_keyboard[] = $keyword_data;
//        $reply_markup = Keyboard::make()->inline($inline_keyboard);
//        $reply_markup = $this->telegram->replyKeyboardMarkup([
//            'inline_keyboard' => $inline_keyboard,
//            'resize_keyboard' => true
//        ]);
            $response = $this->telegram->sendMessage([
                'text' => $msg,
                'parse_mode' => 'html',
                'reply_markup' => json_encode(['inline_keyboard' => $inline_keyboard]),
                'chat_id' => $chat_id
            ]);
            return;
        } catch (TelegramResponseException $e) {
            $errorData = $e->getResponseData();
            if ($errorData['ok'] === false) {
                $this->telegram->sendMessage([
                    'chat_id' => 998130354,
                    'text' => 'There was an error for a user. ' . $errorData['error_code'] . ' ' . $errorData['description'],
                ]);
            }
        }
        return;
    }

    public function getSubCategory($cat_id, $chat_id) {
        $catgory = Category::where('id', $cat_id)->first();
        if (count($catgory->allChildren()) > 1) {
            $this->getCategory($chat_id, $catgory->allChildren());
        } else {
            $this->getCatProducts($chat_id, $catgory->childProducts());
        }
        return;
        exit();
    }

    public function getCatProducts($chat_id, $products) {
        $product_data = array();
        foreach ($products as $product) {
            $product_data_value['callback_data'] = 'produ__' . $product->id;
            $product_data_value['text'] = $product->name;
            $product_data[] = $product_data_value;
        }
        $msg = "Select 📦 Product From list";
        $this->inlineKeyword($chat_id, $product_data, $msg);
        $msg = "Or select bellow option";
        $this->keyword($chat_id, $msg);
        return;
        exit();
    }

    public function getSingleProduct($chat_id, $product_id) {
        $product = Product::where('id', $product_id)->first();
        $product->frontImage();
        try {
            $resource = public_path();
            $resourceOrFile = public_path('storage/' . $product->frontImage()->image);
            $this->telegram->sendPhoto([
                'chat_id' => $chat_id,
                'photo' => InputFile::create($resourceOrFile),
                'caption' => $product->name,
            ]);
        } catch (\Exception $e) {
            $this->telegram_error_message = $e->getMessage();
            $this->telegram_error_code = $e->getCode();

            $this->response = false;
        }
        $offers = Offer::where('product_id', $product_id)->get();
        $symbol = $product->getLocalSymbol();
        $mesure = $product->mesure;


        foreach ($offers as $offer) {
            $offer_data_value['callback_data'] = 'add_cart__' . $offer->id;
            $offer_data_value['text'] = '✅ Add to cart ' . $symbol . $offer->price . '--' . $offer->min_quantity . '/' . $mesure;
            $offer_data[] = $offer_data_value;
        }


        $msg = "Select Quantity offer ";
        $this->inlineKeyword($chat_id, $offer_data, $msg);
        $msg = "Select Quantity offer or select option bellow ";
        $this->keyword($chat_id, $msg);
        return;
        exit();
    }

    /**
     * Add or edit item to cart
     *
     * @param NewItemRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart($offer_id, $chat_id, $from) {
        $offer = Offer::where('id', $offer_id)->first();
        $product = Product::where('id', $offer->product_id)->first();
        $user = User::where('telegram_username', $from)->first();
        if ($user == null) {
            $response = $this->telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Hello ' . $from . ' You can not add to cart Your profile not found  please visit https://demo.botdigit.com and setup your telegram profile '
            ]);
            $msg = "Hello";
            $this->keyword($chat_id, $msg);
            return;
        } else {
            $cart_items = DB::table('telegram_cart')->where('product', $product->id)->where('user_id', $user->id)->get();

            if ($cart_items->isEmpty()) {
                DB::table('telegram_cart')->insertOrIgnore([
                    ['product' => $product->id, 'user_id' => $user->id, 'quantity' => $offer->min_quantity]
                ]);
            } else {
                foreach ($cart_items as $cart_item) {
                    DB::table('telegram_cart')->where('id', $cart_item->id)->update(['quantity' => $cart_item->quantity + $offer->min_quantity]);
                }
            }

            $response = $this->telegram->sendMessage([
                'chat_id' => $chat_id,
                'show_alert' => true,
                'text' => $product->name . ' is added to cart successfully'
            ]);
            $this->getCart($chat_id, $from);
            return;
        }
    }

    public function getCart($chat_id, $from) {
        $user = User::where('telegram_username', $from)->first();
        if ($user == null) {
            $msg = "Hello please check setting to configure username";
            $this->keyword($chat_id, $msg);
            return;
        } else {
            $cart_items = DB::table('telegram_cart')->where('user_id', $user->id)->get();
            if (!$cart_items->count()) {
                $msg = "Cart is Empty";
                $this->keyword($chat_id, $msg);
                return;
            } else {
                foreach ($cart_items as $cart_item) {
                    $product = Product::where('id', $cart_item->product)->first();
                    $cart_data_value['callback_data'] = 'remove_cart__' . $cart_item->id;
                    $cart_data_value['text'] = '❌Remove: ' . $product->name . ' * ' . $cart_item->quantity . '-' . $product->mesure;
                    $cart_data[] = $cart_data_value;
                }
                if (count($cart_items) == 1) {
                    $msg = "You can click on checkout" . PHP_EOL . "Your cart Item: ";
                    $this->inlineKeyword($chat_id, $cart_data, $msg);
                } else {
                    // 
                    $msg = "You can click on checkout" . PHP_EOL . "Your cart Item: ";
                    $this->inlineKeyword($chat_id, $cart_data, $msg);
                    // $msg = "Make sure you can checkout single item in one order please remove extra before checkout" . PHP_EOL . "Your cart Items: ";
                    // $this->inlineKeyword($chat_id, $cart_data, $msg);
                }
                $msg = "Click checkout and follow instruction:";
                $this->checkOutKeyword($chat_id, $msg);
                return;
            }
            return;
        }
        return;
    }

    public function removeCart($item_id, $chat_id, $from) {
        DB::table('telegram_cart')->where('id', $item_id)->delete();
        $this->getCart($chat_id, $from);
        return;
    }

    public function getShiping($chat_id = '931172932', $from = 'Priyesh_7', $completed_item = null) {
        
        
        
        $user = User::where('telegram_username', $from)->first();
        $cart_items = DB::table('telegram_cart')->where('user_id', $user->id)->where('shippings', '=', null)->get();
        foreach ($cart_items as $cart_item) {
            $product = Product::where('id', $cart_item->product)->first();
            $product_name = $product->name;
            $shipping = null;
            if ($product->isPhysical()) {
                $shippings = Shipping::where('product_id', $product->id)->where('deleted', '=', 0)->where('from_quantity', '<=', $cart_item->quantity)->where('to_quantity', '>=', $cart_item->quantity)->get();
                foreach ($shippings as $shipping) {
                    $shipment_data_value['callback_data'] = 'shipmentq__' . $shipping->id;
                    $shipment_data_value['text'] = '🚚' . $shipping->name . ' ' . $shipping->duration . ' ' . $shipping->price;
                    $shipment_data[] = $shipment_data_value;
                }
                $msg = "Select 🚚 Shipment from List for Item(a): " . $product_name;
                $this->inlineKeyword($chat_id, $shipment_data, $msg);
                return;
            }
        }
        $this->askAddress($chat_id, $from);
    }

    public function getShipmentLoop($chat_id = '931172932', $from = 'Priyesh_7', $shipment_id = null) {
        $user = User::where('telegram_username', $from)->first();
        $left_cart_items = DB::table('telegram_cart')->where('user_id', $user->id)->where('shippings', '=', null)->get();
        $total_cart_items = count($left_cart_items);
        $shipping = null;
        foreach ($left_cart_items as $key => $cart_item) {
            $product = Product::where('id', $cart_item->product)->first();
            $product_name = $product->name;
            if ($product->isPhysical()) {
                if ($shipment_id) {
                    $user = User::where('telegram_username', $from)->first();
                    $update_shipping = DB::table('telegram_cart')->where('id', $cart_item->id)->update(['shippings' => $shipment_id]);
                    $shipment_id = null;
                    $this->getShipmentLoop($chat_id, $from);
                    break;
                }
                $shippings = Shipping::where('product_id', $product->id)->where('deleted', '=', 0)->where('from_quantity', '<=', $cart_item->quantity)->where('to_quantity', '>=', $cart_item->quantity)->get();
                foreach ($shippings as $shipping) {
                    $shipment_data_value['callback_data'] = 'shipmentq__' . $shipping->id;
                    $shipment_data_value['text'] = '🚚' . $shipping->name . ' ' . $shipping->duration . ' ' . $shipping->price;
                    $shipment_data[] = $shipment_data_value;
                }
                $msg = "Select 🚚 Shipment from List for Item:" . $product_name;
                $this->inlineKeyword($chat_id, $shipment_data, $msg);
                return;
            } else {
                $user = User::where('telegram_username', $from)->first();
                $update_shipping = DB::table('telegram_cart')->where('id', $cart_item->id)->update(['shippings' => "digital"]);
                $shipment_id = null;
                $this->getShipmentLoop($chat_id, $from);
                break;
            }
        }
        if ($total_cart_items == 0) {
            $this->askAddress($chat_id, $from);
            return; 
        }
    }

    public function askAddress($chat_id, $from, $shipment_id = NULL) {
        $text = "Please send your address by replying to this message now. Use the following format when writing your address." . PHP_EOL;
        $text .= PHP_EOL;
        $text .= "<pre>Miss S Pollard" . PHP_EOL;
        $text .= "1 Chapel Hill" . PHP_EOL;
        $text .= "Heswall" . PHP_EOL;
        $text .= "BOURNEMOUTH" . PHP_EOL;
        $text .= "BH1 1AA" . PHP_EOL;
        $text .= "UNITED KINGDOM" . PHP_EOL;
        $text .= "Mr P Kunde" . PHP_EOL;
        $text .= "Lange Str. 12" . PHP_EOL;
        $text .= "04103 LEIPZIG" . PHP_EOL;
        $text .= "GERMANY </pre>" . PHP_EOL;
        $text .= PHP_EOL;

        $text .= "If you have entered your information successfully a confirmation message will appear then follow the instructions to complete order.";
        try {
            $this->telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);

            $forceReply = Keyboard::forceReply(['force_reply' => true]);
            $message = "Add your Address:";
            $this->telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => $forceReply
            ]);
            return;
        } catch (TelegramResponseException $e) {

            $errorData = $e->getResponseData();

            if ($errorData['ok'] === false) {
                $this->telegram->sendMessage([
                    'chat_id' => 998130354,
                    'text' => 'There was an error for a user. ' . $errorData['error_code'] . ' ' . $errorData['description'],
                ]);
            }
        }
        return;
    }

    public function checkoutCall($chat_id, $from, $message = null) {

        $user = User::where('telegram_username', $from)->first();
        $cart_items = DB::table('telegram_cart')->where('user_id', $user->id)->get();
        $text = '';
        $i = 1;
        foreach ($cart_items as $cart_item) {
            DB::table('telegram_cart')->where('id', $cart_item->id)->update(['message' => $message]);
            $product = Product::where('id', $cart_item->product)->first();
            $shipping_id = $cart_item->shippings;
            $shipping = null;
            $quantity = $cart_item->quantity;
            $coin = 'btc';
            $newCartItem = new Purchase;
            $newCartItem->id = \Uuid::generate()->string; // generate id for address
            $newCartItem->setBuyer($user);
            $newCartItem->setVendor($product->user->vendor);
            $newCartItem->setOffer($product->bestOffer($quantity));
            $newCartItem->setShipping($shipping);
            if ($product->isPhysical()) {
                $shipping = Shipping::where('id', $cart_item->shippings)->where('deleted', '=', 0)->first();
                $newCartItem->setShipping($shipping);
            }
            $newCartItem->message = $message ?? '';
            $newCartItem->quantity = $quantity;
            $newCartItem->coin_name = $coin;
            $newCartItem->type = 'normal';
            $symbol = $product->getLocalSymbol();
            $mesure = $product->mesure;
            $text .= "You are check out for:" . PHP_EOL . PHP_EOL;
            $text .= "<code>(" . $i . ")" . $product->name . '  **  ' . $quantity . ' ' . $mesure . '</code>' . PHP_EOL . PHP_EOL;
            $itemsTable[$product->id] = $newCartItem;
            $i++;
        }
        $total = $this->total($itemsTable);
        $text .= "Total:" . $symbol . $total . PHP_EOL;

        $cart_data_value['callback_data'] = 'final_checkout__';
        $cart_data_value['text'] = 'Checkout (' . $symbol . $total . ')';
        $cart_data[] = $cart_data_value;
        $this->inlineKeyword($chat_id, $cart_data, $text);
        return;
    }

    public function checkout($chat_id, $from) {

        $user = User::where('telegram_username', $from)->first();
        $cart_items = DB::table('telegram_cart')->where('user_id', $user->id)->get();

        $text = '';
        $i = 1;
        foreach ($cart_items as $cart_item) {
            $product = Product::where('id', $cart_item->product)->first();
            $shipping_id = $cart_item->shippings;
            $shipping = null;
            $quantity = $cart_item->quantity;
            $coin = 'btc';
            $newCartItem = new Purchase;
            $newCartItem->id = \Uuid::generate()->string; // generate id for address
            $newCartItem->setBuyer($user);
            $newCartItem->setVendor($product->user->vendor);
            $newCartItem->setOffer($product->bestOffer($quantity));
            $newCartItem->setShipping($shipping);
            if ($product->isPhysical()) {
                $shipping = Shipping::where('id', $cart_item->shippings)->where('deleted', '=', 0)->first();
                $newCartItem->setShipping($shipping);
            }
            $newCartItem->setShipping($shipping);
            $newCartItem->message = ($message) ?? '';
            $newCartItem->quantity = $quantity;
            $newCartItem->coin_name = $coin;
            $newCartItem->type = 'normal';
            $text .= "(" . $i . ")" . $product->name . '-' . $quantity . PHP_EOL;
            $itemsTable[$product->id] = $newCartItem;
            $i++;
        }
        if (isset($itemsTable)) {
            $purchased_item = $this->finalPurchase($itemsTable, $user);
        }
        $this->purchases($chat_id, $from);
    }

    public function total($items) {
        $totalSum = 0;
        foreach ($items as $productId => $item) {
            $totalSum += $item->value_sum;
        }
        return $totalSum;
    }

    public function finalPurchase($items, $user) {
        try {
// Begin a transaction
            $purchases = DB::table('purchases')->where('buyer_id', $user->id)->get();

            DB::beginTransaction();

            foreach ($items as $productId => $item) {
// Purchase procedure
                $purchased_item = $item->purchased();
                $balance = $user->balance;
                if ($balance >= $item->to_pay) {
                    $wallet_pay = $user->Withdraw($item->to_pay);
                }
// Persist the purchase
                $item->save();
            }
            DB::commit();
            DB::table('telegram_cart')->where('user_id', $user->id)->delete();
            if (isset($wallet_pay)) {
                $purchase = Purchase::find($item->id);
                $purchase->state = 'sent';
                $purchase->save();
            }
            return $purchased_item;
        } catch (\Exception $e) {
// An error occured; cancel the transaction...
            DB::rollback();
            Log::error($e);
// and throw the error again.
            throw $e;
        }
    }

    public function purchases($chat_id, $from, $state = '') {
        $user = User::where('telegram_username', $from)->first();
        $purchases = $user->purchases()->orderByDesc('created_at')->paginate(20);

        if (array_key_exists($state, Purchase::$states))
            $purchases = $user->purchases()->where('state', $state)->orderByDesc('created_at')->paginate(20);

        foreach ($purchases as $purchase) {
            $purchase_data_value['callback_data'] = 'purchase__' . $purchase->id;
            if ($purchase->state == 'purchased') {
                $state = $purchase->state . ' ✅';
            } elseif ($purchase->state == 'sent') {
                $state = $purchase->state . ' ✅';
            } elseif ($purchase->state == 'completed') {
                $state = $purchase->state . ' ✅';
            }
            $purchase_data_value['text'] = $purchase->id . ' (' . $state . ')';
            $purchase_data[] = $purchase_data_value;
        }
        if (isset($purchase_data)) {
            $msg = "Please select order to check payment and order status" . PHP_EOL;
            $msg .= "Your Orders:";
            $this->inlineKeyword($chat_id, $purchase_data, $msg);
        } else {
            $this->askAddress($chat_id, $from);
            return;
        }
    }

    public function singlePurchase($purchase_id, $chat_id, $from) {
        $purchase = Purchase::where('id', $purchase_id)->first();
        $offer = Offer::where('id', $purchase->offer_id)->first();
        $product = Product::where('id', $offer->product_id)->first();
        $symbol = $product->getLocalSymbol();
        $mesure = $product->mesure;
        $text = "Order ID: " . $purchase->id . PHP_EOL;
        $text .= "Your Item:" . PHP_EOL;
        $text .= $product->name . " " . $purchase->quantity . " " . $mesure . PHP_EOL . PHP_EOL;
        $text .= "Transfer <code>" . $purchase->to_pay . "</code> to Bitcoin: <code>" . $purchase->address . "</code>" . PHP_EOL . PHP_EOL;
        $text .= "WARNING — Please send EXACT amount shown above within 30 minutes or your payment will not be recognised by the bot and may experience delays!Do not underpay or overpay, the bot will send you a payment confirmation message if you sent the correct amount inside the payment window.";
        $response = $this->telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'html'
        ]);
        $msg = "Select option or for help add your message in bug/help ";
        $this->keyword($chat_id, $msg);
        return;
    }

    public function sendPGP($chat_id) {
        try {
            $resourceOrFile = public_path('pgp.txt');
            $this->telegram->sendDocument([
                'chat_id' => $chat_id,
                'document' => InputFile::create($resourceOrFile),
                'caption' => "PGP file ",
            ]);
            return;
        } catch (\Exception $e) {
            $this->telegram_error_message = $e->getMessage();
            $this->telegram_error_code = $e->getCode();
            $this->response = false;
            return;
        }
    }

    public function help($chat_id, $from) {

    }

    public function sendMsg() {
//        $this->singlePurchase('e4bfa740-68f7-11ea-9381-df4e568a35a0', 998130354, 'develoerhacker');
        $this->getShiping(998130354, 'develoerhacker2');
        $this->getCart(931172932, 'Priyesh_7');
        $this->getSingleProduct(998130354, 'c9b44a00-8142-11ea-a013-316ef7dafd31');
        $parent_category = Category::where('id', '8c4b6d70-7c63-11ea-a098-5ba93f36f040')->first();
        dd(count($parent_category->allChildren()));

        if (count($parent_category->allChildren()) > 1) {
            $this->getCategory(998130354, $parent_category->allChildren());
        } else {
            $this->getCatProducts($chat_id, $parent_category->childProducts());
        }

        $childern = $parent_category->allChildren();
        $params['url'] = 'https://demo.botdigit.com/990763276:AAE0KlxkETs6JD6JLTWpTMt-LTaGxL-Ywj8/webhook';
        dd($this->telegram->setWebhook($params));
        $product = Product::frontPage();
        $sLink = "";
        $array = [];
        foreach ($product->items() as $item) {
            $sLink .= "<a href = '" . $item->id . "'>" . $item->name . "</a>";
            $array[] = $item->id;
        }
        $Text = $sLink;
        $response = $this->telegram->sendMessage([
            'chat_id' => 998130354,
            'text' => $Text,
            'parse_mode' => 'html',
            'reply_markup' => json_encode(['inline_keyboard' => array($array)])
        ]);
        $this->telegram->removeWebhook();
        $params['url'] = 'https://demo.botdigit.com/990763276:AAE0KlxkETs6JD6JLTWpTMt-LTaGxL-Ywj8/webhook';
        dd($this->telegram->setWebhook($params));
        $responses = $this->getUpdate();
        dd($responses);
        foreach ($responses as $response) {
            $chat_id = $response['message']['from']['id'];
            $text = $response['message']['text'];
            $from = $response['message']['from']['username'];

            $user = User::where('telegram_username', $from)->first();
            if ($text == '/start') {
                if ($user != NULL) {
                    $response = $this->telegram->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Hello ' . $from . ' Welcome to Rolling Market'
                    ]);
                } else {
                    $response = $this->telegram->sendMessage([
                        'chat_id' => $chat_id,
                        'text' => 'Hello ' . $from . ' Welcome to the Rolling Market. Your telegram config not found please visit http://lghb2g3jsk4gcxnqmvlnuvggwsvlwamfr6z726b73fqwx4ikvnesxwid.onion/ and setup your telegram profile '
                    ]);
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $response = $this->telegram->getMe();
        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function show(Telegram $telegram) {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function edit(Telegram $telegram) {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Telegram $telegram) {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function destroy(Telegram $telegram) {
//
    }

//      public function callWebhook($responses = null) {
//
//        $response = $this->telegram->getWebhookUpdates();
//
//
//        if (isset($response['message'])) {
//
//            $chat_id = $response['message']['from']['id'];
//            $text = $response['message']['text'];
//            $from = $response['message']['from']['username'];
//            $user = User::where('telegram_username', $from)->first();
//            if ($text == '/start') {
//                if ($user != NULL) {
//                    $response = $this->telegram->sendMessage([
//                        'chat_id' => $chat_id,
//                        'text' => 'Hello ' . $from . ' Welcome to Rolling Market'
//                    ]);
//                    $msg = "Hello";
//                    $this->keyword($chat_id, $msg);
//                } else {
//                    $response = $this->telegram->sendMessage([
//                        'chat_id' => $chat_id,
//                        'text' => 'Hello ' . $from . ' Welcome to Rolling Market. Your telegram config not found please visit http://lghb2g3jsk4gcxnqmvlnuvggwsvlwamfr6z726b73fqwx4ikvnesxwid.onion/ and setup your telegram profile '
//                    ]);
//                    $msg = "Hello";
//                    $this->keyword($chat_id, $msg);
//                }
//            } elseif (isset($response['message']['reply_to_message'])) {
//                if ($response['message']['reply_to_message']['text'] == "Add your Address:") {
//                    $this->checkoutCall($chat_id, $from, $response['message']['text']);
//                }
//            } elseif ($text == '/help') {
//                $response = $this->telegram->sendMessage([
//                    'chat_id' => $chat_id,
//                    'text' => 'Hello ' . $from . '  '
//                ]);
//                $msg = "Hello";
//                $this->keyword($chat_id, $msg);
//            } elseif ($text == 'Cart') {
//                $this->getCart($chat_id, $from);
//            } elseif ($text == 'Products') {
//
//                $this->getCategory($chat_id);
//            } elseif ($text == 'Orders') {
//                $this->purchases($chat_id, $from);
//            } elseif ($text == '/Checkout') {
//                $this->getShiping($chat_id, $from);
//            } else {
//                $this->keyword($chat_id, $from);
//            }
//        } elseif (isset($response['callback_query'])) {
//
//            $chat_id = $response['callback_query']['from']['id'];
//            $from = $response['callback_query']['from']['username'];
//            $callback_text = $response['callback_query']['data'];
//
//            if (preg_match("/[\s]*(categ__)/i", $callback_text)) {
//                $cat_id = str_replace("categ__", '', $callback_text);
//                $this->getSubCategory($cat_id, $chat_id);
//            } elseif (preg_match("/[\s]*(produ__)/i", $callback_text)) {
//                $product_id = str_replace("produ__", '', $callback_text);
//                $this->getSingleProduct($chat_id, $product_id);
//            } elseif (preg_match("/[\s]*(add_cart__)/i", $callback_text)) {
//                $product_id = str_replace("add_cart__", '', $callback_text);
//                $this->addToCart($product_id, $chat_id, $from);
//            } elseif (preg_match("/[\s]*(remove_cart__)/i", $callback_text)) {
//                $cart_item_id = str_replace("remove_cart__", '', $callback_text);
//                $this->removeCart($cart_item_id, $chat_id, $from);
//            } elseif (preg_match("/[\s]*(purchase__)/i", $callback_text)) {
//                $purchase_id = str_replace("purchase__", '', $callback_text);
//                $this->singlePurchase($purchase_id, $chat_id, $from);
//            } elseif (preg_match("/[\s]*(shipment__)/i", $callback_text)) {
//                $shipment_id = str_replace("shipment__", '', $callback_text);
//                $this->singlePurchase($shipment_id, $chat_id, $from);
//            } elseif (preg_match("/[\s]*(final_checkout__)/i", $callback_text)) {
//                $this->checkout($chat_id, $from);
//            }
//        }
//    }

// test api billavenue

    function generateRandomString($length = 35) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function test_api(){
        $plainText = '<?xml version="1.0" encoding="UTF-8"?><billerInfoRequest><billerId>NA7420055XSZ41</billerId></billerInfoRequest>';
        $key = "";
        $encrypt_xml_data = encrypt($plainText, $key);

        $data['accessCode'] = "";
        $data['requestId'] = $this->generateRandomString();
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = "";

        $parameters = http_build_query($data);

        $url = "https://stgapi.billavenue.com/billpay/extMdmCntrl/mdmRequest/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        echo $result . "////////////////////";
        $response = decrypt($result, $key);

        echo "<pre>"; print_r($result);
        die;
        echo "<pre>";
        echo htmlentities($response);
        exit;
    }


}
