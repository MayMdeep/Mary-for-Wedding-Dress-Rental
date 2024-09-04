<?php
namespace App\Actions\Auth;
use App\Implementations\UserImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\Response;
use Carbon\Carbon;
class DashboardLoginAction
{
    use AsAction;
    use Response;
    private $user;
    
    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle(array $data)
    {
        if(Auth::attempt(['username' => $data['username'], 'password' => $data['password']])){
            $user = Auth::user();
			$user = $this->user->update(['login_date'=>Carbon::now()],$user->id);
            $user->permissions = Auth::user()->role->permissions->pluck('name')->toArray();
            $success =  $user;
            $success['token'] =  $user->createToken('Shake')->plainTextToken;
   
            return ['success'=>true,'data'=>$success];
        }elseif (Auth::attempt(['phone' => $data['username'], 'password' => $data['password']])) {

            if (strpos($data['username'], '0') === 0) {
                $data['username'] = ltrim($data['username'], '0');
            }
            $user = Auth::user();
            $user = $this->user->update(['login_date' => Carbon::now()], $user->id);
            $user->permissions = Auth::user()->role->permissions->pluck('name')->toArray();
            $success = $user;
            $success['token'] = $user->createToken('Shake')->plainTextToken;

            return ['success' => true, 'data' => $success];

        }return ['success' => false, 'data' => ''];
    }
    public function rules()
    {
        return [
            'username' => ['required'],
            'password' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
        $validator->after(function (Validator $validator) use ($request) {
            $users = $this->user->getList(['username'=>$request->get('username')]);
            if ($users->isEmpty()) {
                $phone = $request->input('username');
                if (strpos($phone, '0') === 0) {
                    $phone = ltrim($phone, '0');
                }
                $request->merge(['username' => $phone]);

                $users = $this->user->getList(['phone' => $phone]);
            }
            if($users->isNotEmpty())
            {
                $user = $users->first();
                if($user->active != 1)
                    $validator->errors()->add('active', 'User is not active');
                if($user->role->website !=0)
                    $validator->errors()->add('website', 'The selected username is invalid.');
            }

        });
    }

    public function asController(Request $request)
    {
        $result = $this->handle($request->all());
        if($result['success'])
            return $this->sendResponse($result['data'], 'User login successfully.');
        else
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
    }
}