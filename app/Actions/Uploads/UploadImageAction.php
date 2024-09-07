<?php
namespace App\Actions\Uploads;

use Intervention\Image\Encoders\AutoEncoder;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class UploadImageAction
{
    use AsAction;
    use Response;

    function __construct()
    {
    }

    public function handle(array $data )
    {
        $image= $data['file'];
        $path = Storage::disk('external')->put($data['folder'] ?? '', $image);
        return ('images/'.$path);
    }
    public function rules()
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }
    public function asController(Request $request)
    {
        $result = $this->handle($request->all());
        return $this->sendResponse($result, 'Photo Uploaded successfully.');
    }
}
