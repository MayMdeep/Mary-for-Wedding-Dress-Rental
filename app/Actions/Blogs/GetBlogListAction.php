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
class GetBlogListAction
{
    use AsAction;
    use Response;
    private $blog;
    
    function __construct(BlogImplementation $BlogImplementation)
    {
        $this->blog = $BlogImplementation;
    }

    public function handle(array $data = [], int $perBlog = 10)
    {
        if (!is_numeric($perBlog))
            $perBlog = 10;
        
        return BlogResource::collection($this->blog->getPaginatedList($data, $perBlog));
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request){}

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('blog.get'))
            return $this->sendError('Forbidden',[],403);

        $list = $this->handle($request->all(),  $request->input('results', 10));
        
        return $this->sendPaginatedResponse($list,'');
    }
}