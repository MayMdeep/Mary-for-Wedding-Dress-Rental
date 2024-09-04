<?php
namespace App\Actions\Balances;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Actions\Auth\UserRegisterAction;
use App\Actions\Invitations\SendInvitationAction;
use App\Actions\Notifications\App\StoreNotificationAction;
use App\Implementations\UserImplementation;
use App\Implementations\BalanceImplementation;
use Hash;
class TransferBalanceAction
{
    use AsAction;
    use Response;
    private $balance;
    private $user;

    function __construct(BalanceImplementation $BalanceImplementation, UserImplementation $UserImplementation)
    {
        $this->balance = $BalanceImplementation;
        $this->user = $UserImplementation;
    }

    public function handle(array $data)
    {
        if($data['amount'] < 0)
            return [false, 'amount_must_be_more_than_0'];

        if($data['amount'] > 1000000)
            return [false, 'amount_must_be_less_than_1000000'];

        if($data['amount'] > auth()->user()->balance())
            return [false, 'not_enough_balance'];

        $data['user_id'] = auth('sanctum')->user()->id;
        $data['move_type'] = 2;
        $data['balance_type'] = 5;
        $data['active'] = 1;

        if(array_key_exists('username', $data) && $data['username'] != '')
        {
            $user = $this->user->getList(['username' => $data['username']])->first();

            $data['to_user_id'] = $user->id;

            StoreBalanceAction::run($data);

            $data['user_id'] = $user->id;
            $data['move_type'] = 1;
            unset($data['to_user_id'] );


            $balance = StoreBalanceAction::run($data);

            $notificationData = [
                'type' => 2,
                'type_id' => $balance->id,
                'user_id' => $balance->user_id,
                'notification_text' => 'receive_balance',
            ];
            StoreNotificationAction::run($notificationData, $balance);
        }else{

            if (strpos($data['phone'], '0') === 0) {
                $data['phone'] = ltrim($data['phone'], '0');
            }

            $users = $this->user->getList(['phone' => $data['phone']]);

            if($users->isNotEmpty())
            {

                $data['to_user_id'] = $users->first()->id;

                StoreBalanceAction::run($data);

                $data['user_id'] = $users->first()->id;
                $data['move_type'] = 1;
                unset($data['to_user_id'] );

                $balance = StoreBalanceAction::run($data);

                $notificationData = [
                    'type' => 2,
                    'type_id' => $balance->id,
                    'user_id' => $balance->user_id,
                    'notification_text' => 'receive_balance',
                ];
                StoreNotificationAction::run($notificationData, $balance);
            }else{
                // Add a stale record for the person and transfer the balanace
                $userData = [
                    'phone' => $data['phone'],
                    'phone_code'=>$data['phone_code'],
                    'active' => 0,
                    'verified' => 0,
                    'role_id' => 4,
                ];
                $user = UserRegisterAction::run($userData);

                $data['to_user_id'] = $user->id;

                StoreBalanceAction::run($data);

                // Add the balance to the stale user
                $data['user_id'] = $user->id;
                $data['move_type'] = 1;
                unset($data['to_user_id'] );
                StoreBalanceAction::run($data);

                // send an invite via sms to the new user number
                SendInvitationAction::run(['phone' => $data['phone'],'phone_code'=> $data['phone_code']]);
            }
        }
        return [true, 'success'];

    }
    function rules()
    {
        return [
            'username' => ['required_without:phone', 'exists:users,username'],
            'phone' => ['required_without:username'],
            'phone_code'=>['required_with:phone'],
            'amount' => ['required', 'numeric'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
        $validator->after(function (Validator $validator) use ($request) {
            if ($request->input('phone')) {

                $phone = $request->input('phone');
                if (strpos($phone, '0') === 0) {
                    $phone = ltrim($phone, '0');
                }

                $request->merge(['phone' => $phone]);

                $users = $this->user->getList(['not_stale' => $request->input('phone'),'phone_code' =>$request->input('phone_code')]);

                if ($users->isEmpty()) {
                    $validator->errors()->add('phone', 'Phone not Exist');
                }elseif($users->first()->id == auth('sanctum')->user()->id)
                    $validator->errors()->add('user', 'can_not_transfer_to_self');

            }elseif ($request->input('username')) {

                $users = $this->user->getList(['username' => $request->input('username')]);

                if($users->isNotEmpty() && $users->first()->id == auth('sanctum')->user()->id)
                    $validator->errors()->add('user', 'can_not_transfer_to_self');
            }
        });
    }


    public function asController(Request $request)
    {
        try{
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.get'))
            return $this->sendError('Forbidden',[],403);

            $result = $this->handle($request->all());

            if($result[0])
                return $this->sendResponse($result[1], 'Balance Transfered successfully.');
            else
                return $this->sendError($result[1]);

		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());

		}
    }
}
