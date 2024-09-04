<?php
namespace App\Actions\Auth;
use App\Implementations\UserImplementation;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;
class CheckIfPasswordRepeatedAction
{
    use AsAction;
    use Response;
    private $user;
    
    function __construct(UserImplementation $UserImplementation)
    {
        $this->user = $UserImplementation;
    }

    public function handle($password, $user_id = 0)
    {
        if($user_id == 0 && !auth('sanctum')->check())
            return 0;
        
        $user = $user_id == 0 ? auth('sanctum')->user() : $this->user->getOne($user_id);
        
        return $user->passwords()->where('password',$password)->count();
    }
}