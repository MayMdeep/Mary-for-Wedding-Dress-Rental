<?php
namespace App\Actions\Balances;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Balance;
use App\Implementations\BalanceImplementation;
use App\Http\Resources\BalanceResource;
use App\Actions\Translations\UpdateTranslationAction;
use Hash;
class ConvertBalanceToPointsAction
{
    use AsAction;
    use Response;
    private $balance;
    
    function __construct(BalanceImplementation $BalanceImplementation)
    {
        $this->balance = $BalanceImplementation;
    }

    public function handle(array $data)
    {
        if(array_key_exists('usd', $data) && $data['usd'] < config('app.conversion_rate'))
            return [ false, 'min_value' ];

        $result = [
            'usd' => $data['usd'] ?? config('app.conversion_rate'),
            'points' => $data['points'] ?? 1,
            'total' => 0,
        ];

        if(array_key_exists('points', $data))
        {
            $result['usd']    = round((float)$data['points'] * (float)config('app.conversion_rate'),2);
            $result['points'] = (float)$data['points'] ;
            $result['total']  = (float)$result['usd'] + (float)config('app.charge_fee');
        }else{
            $result['points']   = round((float)$data['usd'] / (float)config('app.conversion_rate'),2);
            $result['usd'] = (float)$data['usd'] ;
            $result['total'] = (float)$result['usd'] + (float)config('app.charge_fee');
        }

        return [ true, $result];
    }
    public function rules(Request $request)
    {
        return [
            'usd' => ['required_without:points','numeric'],
            'points' => ['required_without:usd','numeric'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('balance.get'))
            return $this->sendError('Forbidden',[],403);
        $result = $this->handle($request->all());

        if($result[0])
            return $this->sendResponse($result[1],'');
        else
            return $this->sendError($result[1],[ 'conversion_rate' => config('app.conversion_rate')]);
    }
}