<?php
namespace App\Actions\Blogs;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\BlogImplementation;
use App\Http\Resources\BlogResource;
use Hash;
class GetBlogAction
{
    use AsAction;
    use Response;
    private $blog;
    
    function __construct(BlogImplementation $BlogImplementation)
    {
        $this->blog = $BlogImplementation;
    }

    public function handle(int $id)
    {
        return new BlogResource($this->blog->getOne($id));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(int $id)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('blog.get'))
            return $this->sendError('Forbidden',[],403);

        $record = $this->handle($id);

        return $this->sendResponse($record,'');
    }
}